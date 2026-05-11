# Sutra SaaS — AI Agent Guidelines 

## Project Identity 
Sutra is a **multi-tenant SaaS platform** targeting Bangladeshi retail and pharmacy businesses. Built as a **Modular Monolith** on Laravel 13 / PHP 8.5 with subdomain-based tenancy. 

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
│   │   ├── Admin/ 
│   │   └── Tenant/ 
│   ├── Requests/ 
│   │   ├── Admin/ 
│   │   ├── Auth/ 
│   │   └── Tenant/ 
│   └── Middleware/ 
├── Services/ # All business logic 
├── Repositories/ 
│   ├── Interfaces/ 
│   └── Eloquent/ 
├── Models/ 
├── Enums/ 
├── Traits/ 
├── Providers/ 
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
├── Application/ 
├── Database/ 
├── Domain/ 
├── Http/ 
│   ├── Controllers/ 
│   └── Requests/ 
├── Infrastructure/ 
├── Providers/ 
├── Resources/ 
├── Routes/ 
└── Tests/ 
``` 

--- 

## Multi-Tenancy Rules 
- Every tenant operates on a unique subdomain: `shop-name.sutra.com` 
- **All tenant models MUST use the `MultiTenant` trait** — this applies a global `shop_id` scope automatically 
- **Never access `shop_id` directly** — always resolve current tenant via: 
  ```php 
  $tenant = app(TenantManager::class)->getTenant(); 
  $shopId = $tenant->id; 
  ``` 

--- 

## Key Conventions 
### Models 
```php 
class Product extends Model 
{ 
    use HasFactory, HasUuid, MultiTenant; 
    protected $fillable = ['uuid', 'shop_id', 'name', 'price', 'status']; 
    protected $casts = [ 
        'status' => ActiveStatus::class, 
    ]; 
} 
``` 

### Enums 
- Defined in `app/Enums/` 
- Always use enums for status fields — never raw strings or integers 

### Traits 
- `HasUuid` — apply to every model (UUIDs enable offline-sync) 
- `MultiTenant` — apply to every tenant-scoped model 

### Validation 
- Always use Form Request classes — never `$request->validate()` inline in controllers 

### Authorization 
- Use Spatie roles/permissions scoped to `shop_id` team 

### Dependency Injection 
- Always inject **interfaces**, never concrete classes: 
  ```php 
  public function __construct( 
      private readonly UserRepositoryInterface $userRepository 
  ) {} 
  ``` 

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

--- 

## Development Workflow 
```bash 
composer run setup # Install deps + migrate + build assets (first time) 
composer run dev # Start Laravel + queue worker + Vite concurrently 
composer run dev:logs # Start Laravel + queue worker + logs + Vite concurrently 
composer run test # Run full test suite 
php artisan test # Same as above 
``` 

--- 

## File Locations Reference 
| Type | Location | 
|------|----------| 
| Admin Controllers | `app/Http/Controllers/Admin/` 
| Tenant Controllers | `app/Http/Controllers/Tenant/` 
| Services | `app/Services/` 
| Repository Interfaces | `app/Repositories/Interfaces/` 
| Repository Implementations | `app/Repositories/Eloquent/` 
| Models | `app/Models/` 
| FormRequests (Admin) | `app/Http/Requests/Admin/` 
| FormRequests (Tenant) | `app/Http/Requests/Tenant/` 
| Enums | `app/Enums/` 
| Traits | `app/Traits/` 
| Frontend Pages | `resources/js/Pages/` 
| Frontend Components | `resources/js/Components/` 
| Feature Tests | `tests/Feature/` 
| Unit Tests | `tests/Unit/` 
| Module Routes | `app/Modules/{Module}/Routes/` 

--- 

## Anti-Patterns — Never Do These 
```php 
// ❌ Business logic in controller 
public function store(Request $request) { 
    $product = Product::create($request->all()); 
    $product->update(['stock' => 100]); 
} 
``` 

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

--- 

## Reference Documents 
| Document | Purpose | 
|----------|---------| 
| `ARCHITECTURE.md` | Full layer architecture with examples 
| `MASTER_CONTEXT.md` | Project-wide business context 
| `QUICK_REFERENCE.md` | Common commands and patterns 
| `RELATIONSHIPS.md` | Model relationships and ERD 
| `THEME_BRANDING_GUIDE.md` | UI colors, fonts, Tailwind tokens 
| `INITIAL_DATABASE_MIGRATIONS.md` | DB schema baseline 
| `docs/tenancy_flow.md` | Multi-tenancy request flow detail 
| `docs/event_engine.md` | Event-driven POS engine 

--- 

## Cache Strategy 
**All caching must use Redis.** 

### Configuration 
- **Default Cache Store**: `redis` (set in `.env` and `config/cache.php`) 
- **Redis Connection**: Defined in `config/database.php` and `.env` 
- **Cache Service**: Centralized `app/Services/CacheService.php` enforces Redis usage. 

### Implementation 
- **Replace all `Cache::` calls** with `CacheService` methods: 
  ```php 
  // Before 
  Cache::put('key', $value, 60); 
  // After 
  $this->cacheService->put('key', $value, 60); 
  ``` 

### Testing 
- **Test Redis Usage**: 
  ```php 
  // tests/Feature/CacheServiceTest.php 
  public function test_redis_is_used(): void 
  { 
      $cacheService = new CacheService(); 
      $cacheService->put('test_key', 'value', 60); 
      $this->assertEquals('value', $cacheService->get('test_key')); 
  } 
  ``` 

## Final Notes 
- **IntelliSense Errors**: Resolved by ensuring proper code formatting and IDE configuration.
