# Sutra SaaS - Quick Reference Guide

## Project Structure

```
Sutra SaaS (Multi-Tenant Laravel Application)
├── Admin Panel (Main Domain: sutra.localhost)
│   ├── Dashboard - System statistics
│   ├── Shops - Manage all shops
│   ├── Users - Manage all users
│   └── Settings - System configuration
│
├── Tenant Subdomains (shop-slug.sutra.localhost)
│   ├── Dashboard - Shop statistics
│   ├── Settings > Users - Manage shop users
│   ├── Finance Module - Financial tracking
│   ├── Inventory Module - Stock management
│   ├── POS Module - Point of Sale
│   └── Reporting Module - Analytics & reports
```

---

## Key Files & Directories

| Path | Purpose |
|------|---------|
| `app/Http/Controllers/Admin/` | Admin panel controllers |
| `app/Http/Controllers/Tenant/` | Tenant dashboard controllers |
| `app/Http/Controllers/Auth/` | Authentication controllers |
| `app/Http/Requests/` | Input validation classes |
| `app/Services/` | Business logic layer |
| `app/Repositories/` | Data access layer |
| `app/Models/` | Database entity models |
| `app/Enums/` | Type-safe enums |
| `app/Traits/` | Reusable model traits |
| `app/Modules/` | Feature modules |
| `routes/web.php` | Application routes |
| `config/` | Configuration files |
| `database/migrations/` | Database schema |

---

## Request Classes

### Location: `app/Http/Requests/`

All HTTP input validation happens here:

```php
class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Or use auth checks
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users'],
        ];
    }
}
```

### Usage in Controllers:
```php
public function store(StoreUserRequest $request)
{
    // Data is already validated
    $data = $request->validated(); // Clean array
    $this->userService->createUser($data);
}
```

---

## Services

### Location: `app/Services/`

Services contain business logic:

```php
final class UserService
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {}

    public function createUser(array $data): User
    {
        // Business logic here
        return $this->userRepository->create($data);
    }
}
```

### Key Points:
- ✅ Inject repositories via constructor
- ✅ Return data, not HTTP responses
- ✅ Handle complex operations
- ✅ Use type hints on all methods

---

## Repositories

### Location: `app/Repositories/`

Repositories abstract data access:

#### Interface:
```php
interface UserRepositoryInterface
{
    public function create(array $data): User;
    public function find(string $id): ?User;
    public function update(User $user, array $data): bool;
    public function delete(User $user): ?bool;
}
```

#### Implementation:
```php
final class UserRepository implements UserRepositoryInterface
{
    public function create(array $data): User
    {
        return User::create($data);
    }

    public function update(User $user, array $data): bool
    {
        return $user->update($data);
    }

    // ... other methods
}
```

### Standard Methods:
- `create(array $data): Model`
- `find(string $id): ?Model`
- `update(Model $model, array $data): bool`
- `delete(Model $model): ?bool`
- `getByShop(string $shopId): Collection` (custom queries)

---

## Controllers

### Location: `app/Http/Controllers/`

Controllers coordinate requests and responses:

```php
final class UserController extends Controller
{
    public function __construct(
        private readonly UserService $userService
    ) {}

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $this->userService->createUser($request->validated());
        return back()->with('success', 'User created.');
    }
}
```

### Responsibility:
1. Inject services via constructor
2. Receive validated request
3. Call service methods
4. Return HTTP response

---

## Models

### Location: `app/Models/`

Models define database entities:

```php
class User extends Authenticatable
{
    use HasFactory, HasUuid, MultiTenant, HasRoles;

    protected $fillable = ['name', 'email', 'password'];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }
}
```

### Key Traits:
- `HasUuid` - Auto-generates UUID on creation
- `MultiTenant` - Scopes queries to current shop
- `HasRoles` (Spatie) - Role-based access control

---

## Enums

### Location: `app/Enums/`

Type-safe enums for status values:

```php
enum ActiveStatus: int
{
    case ACTIVE = 1;
    case INACTIVE = 0;
}

enum BusinessType: int
{
    case RETAIL = 1;
    case WHOLESALE = 2;
    case RESTAURANT = 3;
}
```

### Usage:
```php
// In database
$shop->status = ActiveStatus::ACTIVE->value;

// In validation
'status' => ['required', Rule::enum(ActiveStatus::class)],

// In models
protected function casts(): array
{
    return ['status' => ActiveStatus::class];
}
```

---

## Traits

### Location: `app/Traits/`

Reusable model behavior:

#### `HasUuid`
Auto-generates UUID on model creation:
```php
$user = User::create(['name' => 'John']);
// $user->uuid is auto-generated
```

#### `MultiTenant`
Scopes all queries to current shop:
```php
User::all(); // Returns only current shop's users
```

---

## Routes

### Location: `routes/web.php`

#### Main Domain Routes (Admin)
```
GET  /                         Welcome page
GET  /admin/dashboard          Admin dashboard
GET  /admin/shops              List shops
PATCH /admin/shops/{shop}      Update shop
GET  /admin/users              List users
GET  /admin/settings           Settings
```

#### Authentication Routes
```
GET  /register                 Registration form
POST /register                 Register tenant
GET  /login                    Login form
POST /login                    Authenticate
POST /logout                   Logout
```

#### Tenant Subdomain Routes
```
GET  /dashboard                Tenant dashboard
GET  /settings/users           List shop users
POST /settings/users           Create user
DELETE /settings/users/{user}  Delete user
```

---

## Dependency Injection Setup

### File: `app/Providers/AppServiceProvider.php`

All services and repositories are registered here:

```php
public function register(): void
{
    // Repository bindings
    $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    $this->app->bind(ShopRepositoryInterface::class, ShopRepository::class);
    // ... more bindings
}
```

### Auto-injection in Controllers:
```php
// Laravel automatically resolves from container
public function __construct(
    private readonly UserService $userService,
    private readonly ShopService $shopService
) {}
```

---

## Common Code Patterns

### Validate & Create
```php
// In controller
public function store(StoreUserRequest $request): RedirectResponse
{
    $this->userService->createUser($request->validated());
    return back()->with('success', 'Created!');
}

// In service
public function createUser(array $data): User
{
    return $this->userRepository->create($data);
}

// In repository
public function create(array $data): User
{
    return User::create($data);
}
```

### Transaction
```php
public function registerTenant(array $shopData, array $userData): User
{
    return DB::transaction(function () use ($shopData, $userData) {
        $shop = $this->shopRepository->create($shopData);
        $userData['shop_id'] = $shop->id;
        $user = $this->userRepository->create($userData);
        // Additional setup
        return $user;
    });
}
```

### Authorization Check
```php
public function deleteUser(User $user, string $shopId): ?bool
{
    if ($user->shop_id !== $shopId) {
        abort(403);
    }
    if ($user->id === auth()->id()) {
        return false;
    }
    return $this->userRepository->delete($user);
}
```

### Eager Loading
```php
public function getAllUsers(): Collection
{
    return User::with('shop', 'roles')->get();
}
```

---

## Adding New Features

### 1. Create Request Class
```bash
php artisan make:request Tenant/StoreProductRequest
```

Edit `app/Http/Requests/Tenant/StoreProductRequest.php`:
```php
public function rules(): array
{
    return [
        'name' => ['required', 'string', 'max:255'],
        'price' => ['required', 'numeric', 'min:0'],
    ];
}
```

### 2. Create Repository Interface
Edit `app/Repositories/Interfaces/ProductRepositoryInterface.php`:
```php
interface ProductRepositoryInterface
{
    public function create(array $data): Product;
    public function update(Product $product, array $data): bool;
    // ... other methods
}
```

### 3. Create Repository Implementation
Edit `app/Repositories/Eloquent/ProductRepository.php`:
```php
final class ProductRepository implements ProductRepositoryInterface
{
    public function create(array $data): Product
    {
        return Product::create($data);
    }
    // ... implement methods
}
```

### 4. Create Service
Edit `app/Services/ProductService.php`:
```php
final class ProductService
{
    public function __construct(
        private readonly ProductRepositoryInterface $repository
    ) {}

    public function createProduct(array $data): Product
    {
        return $this->repository->create($data);
    }
}
```

### 5. Register in AppServiceProvider
```php
$this->app->bind(
    ProductRepositoryInterface::class,
    ProductRepository::class
);
```

### 6. Create Controller
```php
final class ProductController extends Controller
{
    public function __construct(
        private readonly ProductService $productService
    ) {}

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $this->productService->createProduct($request->validated());
        return back()->with('success', 'Product created!');
    }
}
```

### 7. Add Route
```php
Route::post('/products', [ProductController::class, 'store']);
```

---

## Testing

### Unit Test (Service)
```php
class ProductServiceTest extends TestCase
{
    public function testCreateProduct()
    {
        $service = new ProductService(
            mock(ProductRepositoryInterface::class)
        );
        $result = $service->createProduct(['name' => 'Widget']);
        $this->assertInstanceOf(Product::class, $result);
    }
}
```

### Feature Test (Controller)
```php
class ProductControllerTest extends TestCase
{
    public function testStoreProduct()
    {
        $response = $this->post('/products', [
            'name' => 'Widget',
            'price' => 99.99,
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('products', ['name' => 'Widget']);
    }
}
```

---

## PSR-12 Style Guide

### Template
```php
<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;

final class UserService
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {}

    /**
     * Create a new user.
     *
     * @param array<string, mixed> $data
     * @return User
     */
    public function createUser(array $data): User
    {
        return $this->userRepository->create($data);
    }
}
```

### Key Points:
- ✅ `declare(strict_types=1);` at top
- ✅ 4-space indentation
- ✅ Type hints on all parameters
- ✅ Type hints on returns
- ✅ Docblocks with `@param` and `@return`
- ✅ Final classes for services/repositories
- ✅ Private properties with `readonly`

---

## Useful Commands

```bash
# Make a request class
php artisan make:request Tenant/StoreUserRequest

# Make a model
php artisan make:model Product -m

# Make a controller
php artisan make:controller UserController

# Make a service class
php artisan make:class Services/UserService

# Run migrations
php artisan migrate

# Seed database
php artisan db:seed

# Run tests
php artisan test

# Run tests with coverage
php artisan test --coverage
```

---

## Resources

- **Architecture Guide:** See `ARCHITECTURE.md`
- **Audit Report:** See `REFACTORING_AUDIT_REPORT.md`
- **Laravel Docs:** https://laravel.com/docs
- **PSR-12 Standard:** https://www.php-fig.org/psr/psr-12/

---

## Support

For questions about the architecture or implementation, refer to:
1. `ARCHITECTURE.md` - Detailed explanation of each layer
2. `REFACTORING_AUDIT_REPORT.md` - List of all changes made
3. Existing code examples in the repository

Happy coding! 🚀

