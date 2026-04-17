# Sutra SaaS Architecture Guide

## Overview

Sutra SaaS is a multi-tenant SaaS platform built with Laravel, Inertia.js, and Vue.js. The application follows a **three-layer architecture pattern**:

1. **Request Layer** - Input validation using dedicated Request classes
2. **Service Layer** - Business logic and domain operations
3. **Repository Layer** - Data access and persistence
4. **Model Layer** - Eloquent models representing database entities

---

## Architecture Diagram

```
┌──────────────────────────────────────────────────────────────┐
│                        HTTP Request                          │
└──────────────────────────────────────────────────────────────┘
                              ↓
┌──────────────────────────────────────────────────────────────┐
│                    Request Class                             │
│            (Validation & Authorization)                      │
└──────────────────────────────────────────────────────────────┘
                              ↓
┌──────────────────────────────────────────────────────────────┐
│                     Controller                               │
│         (Coordinates request & response handling)            │
└──────────────────────────────────────────────────────────────┘
                              ↓
┌──────────────────────────────────────────────────────────────┐
│                   Service Layer                              │
│           (Business Logic & Domain Operations)               │
└──────────────────────────────────────────────────────────────┘
                              ↓
┌──────────────────────────────────────────────────────────────┐
│                 Repository Layer                             │
│              (Data Access & Persistence)                     │
└──────────────────────────────────────────────────────────────┘
                              ↓
┌──────────────────────────────────────────────────────────────┐
│                   Eloquent Models                            │
│                 (Database Entities)                          │
└──────────────────────────────────────────────────────────────┘
```

---

## Directory Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   │   ├── DashboardController.php
│   │   │   ├── ShopController.php
│   │   │   └── UserController.php
│   │   ├── Tenant/
│   │   │   └── UserController.php
│   │   ├── Auth/
│   │   │   ├── RegisteredUserController.php
│   │   │   └── AuthenticatedSessionController.php
│   │   ├── Controller.php
│   │   └── ProfileController.php
│   ├── Requests/
│   │   ├── Admin/
│   │   │   └── ShopUpdateRequest.php
│   │   ├── Auth/
│   │   │   ├── LoginRequest.php
│   │   │   └── TenantRegisterRequest.php
│   │   ├── Tenant/
│   │   │   └── StoreUserRequest.php
│   │   ├── ProfileDestroyRequest.php
│   │   └── ProfileUpdateRequest.php
│   └── Middleware/
├── Services/
│   ├── AdminUserService.php
│   ├── DashboardService.php
│   ├── ShopService.php
│   ├── TenantRegistrationService.php
│   ├── TenantUserService.php
│   └── UserService.php
├── Repositories/
│   ├── Interfaces/
│   │   ├── DailySummaryRepositoryInterface.php
│   │   ├── InventoryLogRepositoryInterface.php
│   │   ├── ProductRepositoryInterface.php
│   │   ├── ShopRepositoryInterface.php
│   │   ├── TransactionLogRepositoryInterface.php
│   │   └── UserRepositoryInterface.php
│   └── Eloquent/
│       ├── DailySummaryRepository.php
│       ├── InventoryLogRepository.php
│       ├── ProductRepository.php
│       ├── ShopRepository.php
│       ├── TransactionLogRepository.php
│       └── UserRepository.php
├── Models/
│   ├── DailySummary.php
│   ├── InventoryLog.php
│   ├── Product.php
│   ├── Shop.php
│   ├── TransactionLog.php
│   └── User.php
├── Enums/
│   ├── ActiveStatus.php
│   ├── BusinessType.php
│   └── Plan.php
├── Traits/
│   ├── HasUuid.php
│   └── MultiTenant.php
├── Providers/
│   ├── AppServiceProvider.php
│   ├── EventServiceProvider.php
│   └── ModuleServiceProvider.php
└── Modules/
    ├── Finance/
    ├── Inventory/
    ├── Pos/
    ├── Reporting/
    └── Shared/
```

---

## Layer Responsibilities

### Request Layer

**Location:** `app/Http/Requests/`

**Responsibility:** Validate input data and authorize requests

**Key Methods:**
- `authorize(): bool` - Determine if the user can perform the request
- `rules(): array` - Define validation rules for incoming data

**Example:**
```php
class ShopUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->hasRole('super-admin');
    }

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::enum(ActiveStatus::class)],
        ];
    }
}
```

**When to Use:**
- ✅ All HTTP requests should use a Request class
- ✅ Centralize validation logic
- ✅ Make authorization decisions
- ✅ Allow method injection in controllers

---

### Controller Layer

**Location:** `app/Http/Controllers/`

**Responsibility:** Coordinate HTTP requests and responses

**Typical Flow:**
1. Receive validated request from Request class
2. Call appropriate service methods
3. Return HTTP response (view, JSON, redirect, etc.)

**Example:**
```php
class ShopController extends Controller
{
    public function __construct(
        private readonly ShopService $shopService
    ) {}

    public function update(ShopUpdateRequest $request, Shop $shop): RedirectResponse
    {
        $this->shopService->update($shop, $request->validated());
        return back()->with('success', 'Shop updated successfully.');
    }
}
```

**Responsibilities:**
- ✅ Inject services via constructor
- ✅ Call service methods with validated data
- ✅ Handle HTTP responses (redirects, views, JSON)
- ✅ NOT handle business logic or data access

---

### Service Layer

**Location:** `app/Services/`

**Responsibility:** Encapsulate business logic and domain operations

**Key Characteristics:**
- Contains application-specific business rules
- Calls repositories for data access
- Returns data (not HTTP responses)
- Can be used by controllers or other services
- Handles complex operations and transactions

**Example:**
```php
final class TenantUserService
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {}

    public function createUser(string $shopId, array $data): User
    {
        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'shop_id' => $shopId,
        ];

        $user = $this->userRepository->create($userData);
        
        // Set permissions
        app(PermissionRegistrar::class)->setPermissionsTeamId($shopId);
        $user->assignRole($data['role']);

        return $user;
    }
}
```

**What Belongs in Services:**
- ✅ Business logic (calculations, transformations)
- ✅ Workflow orchestration
- ✅ Data aggregation from multiple repositories
- ✅ Transaction management
- ✅ External API calls
- ❌ HTTP request/response handling
- ❌ Direct database queries (use repositories instead)
- ❌ Data validation (use Request classes instead)

---

### Repository Layer

**Location:** `app/Repositories/`

**Responsibility:** Provide an interface for data access

**Pattern:** Interface-based repositories with Eloquent implementations

**Key Methods (standard CRUD):**
```php
interface UserRepositoryInterface
{
    public function create(array $data): User;
    public function find(string $id): ?User;
    public function update(User $user, array $data): bool;
    public function delete(User $user): ?bool;
}
```

**Example Implementation:**
```php
final class UserRepository implements UserRepositoryInterface
{
    public function create(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'shop_id' => $data['shop_id'],
        ]);
    }

    public function update(User $user, array $data): bool
    {
        return $user->update($data);
    }

    public function delete(User $user): ?bool
    {
        return $user->delete();
    }
}
```

**Benefits:**
- ✅ Abstract data access from business logic
- ✅ Easy to swap implementations (e.g., database vs. API)
- ✅ Testable (mock repositories in unit tests)
- ✅ Consistent data access patterns
- ✅ Centralized query logic

---

### Model Layer

**Location:** `app/Models/`

**Responsibility:** Define database entities and relationships

**Traits & Features:**
- `HasFactory` - Testing support
- `HasUuid` - Automatic UUID generation
- `MultiTenant` - Tenant-aware queries
- `HasRoles` (Spatie) - Role-based access control

**Example:**
```php
class User extends Authenticatable
{
    use HasFactory, HasUuid, MultiTenant, HasRoles;

    protected $fillable = [
        'uuid', 'shop_id', 'name', 'email', 'password', 'status',
    ];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function transactionLogs(): HasMany
    {
        return $this->hasMany(TransactionLog::class);
    }
}
```

---

## Dependency Injection

Services and repositories are registered in the **Service Container** (`app/Providers/AppServiceProvider.php`):

```php
class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Repository bindings
        $this->app->bind(ShopRepositoryInterface::class, ShopRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        // ... more bindings
    }
}
```

**Why:**
- ✅ Loose coupling between classes
- ✅ Easy to swap implementations
- ✅ Automatic injection in controllers
- ✅ Testable with mock implementations

**Usage in Controllers:**
```php
public function __construct(
    private readonly ShopService $shopService
) {}
```

---

## Data Flow Example: Update Shop

### Request
```http
PATCH /admin/shops/{shop}
Content-Type: application/json

{
    "status": 1
}
```

### Flow Through Layers

**1. Request Class** (`ShopUpdateRequest.php`)
```php
// Validates that 'status' is valid enum value
public function rules(): array
{
    return [
        'status' => ['required', Rule::enum(ActiveStatus::class)],
    ];
}
```

**2. Controller** (`ShopController.php`)
```php
public function update(ShopUpdateRequest $request, Shop $shop): RedirectResponse
{
    // Request is already validated
    $this->shopService->update($shop, $request->validated());
    return back()->with('success', 'Shop updated successfully.');
}
```

**3. Service** (`ShopService.php`)
```php
public function update(Shop $shop, array $data): bool
{
    // Business logic (can be expanded)
    return $this->shopRepository->update($shop, $data);
}
```

**4. Repository** (`ShopRepository.php`)
```php
public function update(Shop $shop, array $data): bool
{
    return $shop->update($data);
}
```

**5. Model** (`Shop.php`)
```php
// Eloquent handles the database UPDATE
$shop->update($data); // UPDATE shops SET status = ... WHERE id = ...
```

**6. Response**
```
Redirect back with success message
```

---

## Best Practices

### ✅ Do's

1. **Use Request Classes for all validation**
   ```php
   // Good
   public function store(StoreUserRequest $request)
   ```

2. **Inject services via constructor**
   ```php
   // Good
   public function __construct(
       private readonly UserService $userService
   ) {}
   ```

3. **Return data from services, not responses**
   ```php
   // Good
   public function getStats(): array
   {
       return ['total' => 100, 'active' => 50];
   }
   ```

4. **Use repository interfaces**
   ```php
   // Good
   private readonly UserRepositoryInterface $userRepository
   
   // Avoid
   private readonly UserRepository $userRepository
   ```

5. **Keep methods focused and single-purpose**
   ```php
   // Good: One responsibility
   public function createUser(string $shopId, array $data): User
   
   // Avoid: Multiple responsibilities
   public function createUserAndSendEmail(...)
   ```

### ❌ Don'ts

1. **Don't put validation in controllers**
   ```php
   // Bad
   public function store(Request $request)
   {
       $request->validate([...]);
   }
   ```

2. **Don't access models directly from controllers**
   ```php
   // Bad
   public function index()
   {
       return view('users', [
           'users' => User::with('shop')->get()
       ]);
   }
   ```

3. **Don't return HTTP responses from services**
   ```php
   // Bad
   public function createUser(): Response
   {
       return response()->json([...]);
   }
   ```

4. **Don't put business logic in models**
   ```php
   // Bad (keep this in service)
   $user = User::create($data);
   $user->assignRole('admin');
   $user->givePermissionTo('manage-shops');
   ```

5. **Don't hardcode dependencies**
   ```php
   // Bad
   $repository = new UserRepository();
   
   // Good
   private readonly UserRepositoryInterface $repository
   ```

---

## Testing Strategy

### Unit Tests for Services

Test business logic in isolation with mocked repositories:

```php
class TenantUserServiceTest extends TestCase
{
    private TenantUserService $service;
    private MockInterface $repository;

    protected function setUp(): void
    {
        $this->repository = mock(UserRepositoryInterface::class);
        $this->service = new TenantUserService($this->repository);
    }

    public function testCreateUser()
    {
        $this->repository->shouldReceive('create')->once()->andReturn(new User());
        
        $user = $this->service->createUser('shop-1', [
            'name' => 'John',
            'email' => 'john@example.com',
            'password' => 'password123',
            'role' => 'staff',
        ]);

        $this->assertInstanceOf(User::class, $user);
    }
}
```

### Feature Tests for Controllers

Test HTTP endpoints end-to-end:

```php
class ShopControllerTest extends TestCase
{
    public function testUpdateShop()
    {
        $shop = Shop::factory()->create();

        $response = $this->patch("/admin/shops/{$shop->id}", [
            'status' => 1,
        ]);

        $response->assertRedirect();
        $this->assertEquals(1, $shop->fresh()->status);
    }
}
```

---

## Adding New Features

### Step-by-Step Guide

#### 1. Create Request Class
```bash
php artisan make:request Tenant/StoreProductRequest
```

#### 2. Create Service
```bash
php artisan make:class Services/ProductService
```

#### 3. Create Repository Interface & Implementation
```bash
php artisan make:interface Repositories/Interfaces/ProductRepositoryInterface
php artisan make:class Repositories/Eloquent/ProductRepository
```

#### 4. Register in AppServiceProvider
```php
$this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
```

#### 5. Create Controller
```bash
php artisan make:controller Tenant/ProductController
```

#### 6. Inject and Use
```php
class ProductController extends Controller
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

---

## Common Patterns

### Paginated Repository Results

```php
interface ProductRepositoryInterface
{
    public function getByShop(string $shopId): Paginator;
}

// In controller
public function index(): Response
{
    return Inertia::render('Products/Index', [
        'products' => $this->productService->getShopProducts(auth()->user()->shop_id),
    ]);
}
```

### Multi-Step Operations

```php
public function registerTenant(array $shopData, array $userData): User
{
    return DB::transaction(function () use ($shopData, $userData) {
        // Step 1: Create shop
        $shop = $this->shopRepository->create($shopData);

        // Step 2: Create user
        $userData['shop_id'] = $shop->id;
        $user = $this->userRepository->create($userData);

        // Step 3: Assign permissions
        app(PermissionRegistrar::class)->setPermissionsTeamId($shop->id);
        $user->assignRole('shop-owner');

        return $user;
    });
}
```

### Authorization in Services

```php
public function deleteUser(User $user, string $shopId): ?bool
{
    // Verify ownership
    if ($user->shop_id !== $shopId) {
        abort(403);
    }

    // Prevent self-deletion
    if ($user->id === auth()->id()) {
        return false;
    }

    return $this->userRepository->delete($user);
}
```

---

## PSR-12 Compliance

All code follows **PSR-12: Extended Coding Style Guide**

### Key Points:
- 4-space indentation
- One blank line between class methods
- Two blank lines before docblocks for methods
- Type hints required on all parameters and returns
- `declare(strict_types=1);` at top of files

### Example:
```php
<?php

declare(strict_types=1);

namespace App\Services;

final class UserService
{
    /**
     * Create a new user.
     *
     * @param array<string, mixed> $data
     * @return User
     */
    public function createUser(array $data): User
    {
        // Implementation
    }
}
```

---

## Conclusion

This architecture provides:
- ✅ Clean separation of concerns
- ✅ Easy to test
- ✅ Easy to maintain
- ✅ Easy to extend
- ✅ Follows Laravel best practices
- ✅ PSR-12 compliant

For questions or issues, refer to the `REFACTORING_AUDIT_REPORT.md` file for a complete list of changes.

