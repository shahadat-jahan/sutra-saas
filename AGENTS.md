# Sutra SaaS — AI Agent Guidelines

## Project Identity

Sutra is a **multi-tenant SaaS platform** targeting Bangladeshi retail and pharmacy businesses.
Built as a **Modular Monolith** on Laravel 13 / PHP 8.5 with subdomain-based tenancy.

**Stack:**
- Backend: PHP 8.5, Laravel 13
- Frontend: React + Inertia.js (NOT Vue — README may be outdated)
- Database: PostgreSQL (with JSONB support — avoid MySQL-specific syntax)
- UI: Tailwind CSS + Shadcn UI (custom colors: `sutra-primary`, `sutra-secondary`, `sutra-accent`)
- Auth/Roles: Spatie Laravel Permission (team-based, scoped per `shop_id`)
- Frontend Routes: Ziggy (`route()` helper available in React)

---

## Architecture Overview

### Request Flow

```
HTTP Request
    ↓
FormRequest (validation + authorization)
    ↓
Controller (thin — coordinates only)
    ↓
Service (all business logic lives here)
    ↓
Repository Interface → Eloquent Implementation
    ↓
Model (Eloquent entity)
```

### Directory Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/          # Super-admin domain controllers
│   │   └── Tenant/         # Subdomain tenant controllers
│   ├── Requests/
│   │   ├── Admin/
│   │   ├── Auth/
│   │   └── Tenant/
│   └── Middleware/
├── Services/               # All business logic
├── Repositories/
│   ├── Interfaces/         # Contracts (always inject these)
│   └── Eloquent/           # Implementations
├── Models/                 # Eloquent models
├── Enums/                  # Type-safe enums (ActiveStatus, BusinessType, Plan)
├── Traits/
│   ├── HasUuid.php         # Auto UUID generation
│   └── MultiTenant.php     # Auto shop_id scoping
├── Providers/
│   ├── AppServiceProvider.php   # Interface bindings
│   ├── EventServiceProvider.php
│   └── ModuleServiceProvider.php
└── Modules/
    ├── Finance/
    ├── Inventory/
    ├── Pos/
    ├── Reporting/
    └── Shared/
```

### Module Internal Structure

Each module under `app/Modules/` follows this layout:

```
app/Modules/Inventory/
├── Application/        # Module-level use cases / app services
├── Database/           # Migrations, seeders, factories
├── Domain/             # Entities, value objects, domain logic
├── Http/
│   ├── Controllers/
│   └── Requests/
├── Infrastructure/     # Eloquent repos, external API integrations
├── Providers/          # Module-specific service providers
├── Resources/          # Additional resources (views, assets)
├── Routes/             # Web and API routes (web.php, api.php)
└── Tests/              # Module-specific tests
```

> **Rule:** New feature inside an existing module → add to that module's layers.
> New cross-cutting concern → add to `app/Services/` or `Shared/` module.

---

## Multi-Tenancy Rules

- Every tenant operates on a unique subdomain: `shop-name.sutra.com`
- **All tenant models MUST use the `MultiTenant` trait** — this applies a global `shop_id` scope automatically
- **Never access `shop_id` directly** — always resolve current tenant via:

```php
$tenant = app(TenantManager::class)->getTenant();
$shopId = $tenant->id;
```

- Admin routes run on the **main domain** with `role:super-admin` middleware
- Tenant routes run on **subdomains** with tenant middleware

### Tenancy Gotchas

- Queue jobs MUST carry tenant context explicitly — the global scope does not apply in queued jobs automatically
- Never use `withoutGlobalScopes()` to bypass tenant scoping unless you have an explicit, documented reason
- Never compare `shop_id` manually in services — use `TenantManager` or rely on the `MultiTenant` trait scope
- Cross-tenant operations (super-admin only) must be explicitly authorized before removing scopes

---

## Key Conventions

### Models

```php
class Product extends Model
{
    use HasFactory, HasUuid, MultiTenant;

    protected $fillable = ['uuid', 'shop_id', 'name', 'price', 'status'];

    protected $casts = [
        'status' => ActiveStatus::class,  // Always cast enums
    ];
}
```

### Enums

- Defined in `app/Enums/`
- Always use enums for status fields — never raw strings or integers
- Examples: `ActiveStatus::ACTIVE`, `BusinessType::RETAIL`, `Plan::BASIC`

### Traits

- `HasUuid` — apply to every model (UUIDs enable offline-sync)
- `MultiTenant` — apply to every tenant-scoped model
- `HasDynamicAttributes` — apply to models with flexible JSONB metadata (e.g., Product.attributes, Customer.profile_data)

### Validation

- Always use Form Request classes — never `$request->validate()` inline in controllers
- Request classes handle both `authorize()` and `rules()`

### Authorization

- Use Spatie roles/permissions scoped to `shop_id` team
- Use `authorize()` in FormRequest for simple cases
- Use Policies for complex model-level authorization

### Dependency Injection

- Always inject **interfaces**, never concrete classes:

```php
// Correct
public function __construct(
    private readonly UserRepositoryInterface $userRepository
) {}

// Wrong
public function __construct(
    private readonly UserRepository $userRepository  // ❌ breaks swappability
) {}
```

- Register all bindings in `AppServiceProvider::register()`

---

## Code Patterns

### Controller (thin — no logic)

```php
final class ProductController extends Controller
{
    public function __construct(
        private readonly ProductService $productService
    ) {}

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $this->productService->create($request->validated());
        return back()->with('success', 'Product created.');
    }

    public function index(): Response
    {
        return Inertia::render('Products/Index', [
            'products' => $this->productService->getShopProducts(),
        ]);
    }
}
```

### Service (all business logic)

```php
final class ProductService
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository
    ) {}

    public function create(array $data): Product
    {
        $shopId = app(TenantManager::class)->getTenant()->id;

        return DB::transaction(function () use ($data, $shopId) {
            $data['shop_id'] = $shopId;
            return $this->productRepository->create($data);
        });
    }
}
```

### Repository Interface

```php
interface ProductRepositoryInterface
{
    public function create(array $data): Product;
    public function find(string $id): ?Product;
    public function update(Product $product, array $data): bool;
    public function delete(Product $product): ?bool;
    public function getByShop(string $shopId): Paginator;
}
```

### Repository Implementation

```php
final class ProductRepository implements ProductRepositoryInterface
{
    public function create(array $data): Product
    {
        return Product::create($data);
    }

    public function getByShop(string $shopId): Paginator
    {
        return Product::where('shop_id', $shopId)->paginate(15);
    }
}
```

---

## Adding a New Feature (Checklist)

Follow this order every time — do not skip steps:

1. **FormRequest** → `php artisan make:request Tenant/StoreProductRequest`
2. **Repository Interface** → `app/Repositories/Interfaces/ProductRepositoryInterface.php`
3. **Repository Implementation** → `app/Repositories/Eloquent/ProductRepository.php`
4. **Bind in AppServiceProvider** → `$this->app->bind(ProductRepositoryInterface::class, ProductRepository::class)`
5. **Service** → `app/Services/ProductService.php`
6. **Controller** → `php artisan make:controller Tenant/ProductController`
7. **Routes** → Add to module's `Routes/` folder (web.php or api.php)
8. **Frontend Page** → `resources/js/Pages/Products/Index.jsx`
9. **Tests** → Unit test the Service (mock repo); Feature test the endpoint

---

## Database

- **PostgreSQL** — do not use MySQL-specific syntax (no backtick identifiers, no `GROUP_CONCAT`)
- Use JSONB columns for flexible/dynamic metadata (e.g., product attributes, pharmacy drug info)
- Always use UUIDs as primary keys (`HasUuid` trait handles this)
- Use `DB::transaction()` for any multi-step write operations
- Migrations: `php artisan make:migration create_products_table --create=products`

---

## Development Workflow

```bash
composer run setup    # Install deps + migrate + build assets (first time)
composer run dev      # Start Laravel + queue worker + Vite concurrently
composer run dev:logs # Start Laravel + queue worker + logs + Vite concurrently
composer run test     # Run full test suite
php artisan test      # Same as above
```

---

## File Locations Reference

| Type | Location |
|------|----------|
| Admin Controllers | `app/Http/Controllers/Admin/` |
| Tenant Controllers | `app/Http/Controllers/Tenant/` |
| Services | `app/Services/` |
| Repository Interfaces | `app/Repositories/Interfaces/` |
| Repository Implementations | `app/Repositories/Eloquent/` |
| Models | `app/Models/` |
| FormRequests (Admin) | `app/Http/Requests/Admin/` |
| FormRequests (Tenant) | `app/Http/Requests/Tenant/` |
| Enums | `app/Enums/` |
| Traits | `app/Traits/` |
| Frontend Pages | `resources/js/Pages/` |
| Frontend Components | `resources/js/Components/` |
| Feature Tests | `tests/Feature/` |
| Unit Tests | `tests/Unit/` |
| Module Routes | `app/Modules/{Module}/Routes/` |

---

## Anti-Patterns — Never Do These

```php
// ❌ Business logic in controller
public function store(Request $request) {
    $product = Product::create($request->all()); // direct model access
    $product->update(['stock' => 100]);           // logic in controller
}

// ❌ Inline validation in controller
$request->validate(['name' => 'required']); // use FormRequest instead

// ❌ Returning HTTP responses from services
public function createProduct(): JsonResponse { ... } // services return data only

// ❌ Querying inside loops (N+1)
foreach ($orders as $order) {
    $order->product->name; // loads product per iteration
}
// ✅ Use eager loading: Order::with('product')->get()

// ❌ Bypassing tenant scope without explicit reason
Product::withoutGlobalScopes()->get(); // dangerous — leaks cross-tenant data

// ❌ Injecting concrete repository
private readonly ProductRepository $repo; // use interface instead

// ❌ Hardcoding shop_id
$data['shop_id'] = auth()->user()->shop_id; // use TenantManager

// ❌ Putting domain logic in Models
// models are data entities only — no business rules
```

---

## PSR-12 Requirements

All files must follow PSR-12 strictly:

```php
<?php

declare(strict_types=1);

namespace App\Services;

final class ProductService
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository
    ) {}

    public function create(array $data): Product
    {
        // implementation
    }
}
```

Key rules: `declare(strict_types=1)` on every file, `final` on Services and Repositories, `readonly` properties, 4-space indent, explicit return types on all methods.

---

## Reference Documents

| Document | Purpose |
|----------|---------|
| `ARCHITECTURE.md` | Full layer architecture with examples |
| `MASTER_CONTEXT.md` | Project-wide business context |
| `QUICK_REFERENCE.md` | Common commands and patterns |
| `RELATIONSHIPS.md` | Model relationships and ERD |
| `THEME_BRANDING_GUIDE.md` | UI colors, fonts, Tailwind tokens |
| `INITIAL_DATABASE_MIGRATIONS.md` | DB schema baseline |
| `docs/tenancy_flow.md` | Multi-tenancy request flow detail |
| `docs/event_engine.md` | Event-driven POS engine |
