# Sutra SaaS - Quick Reference Guide

## Project Structure (Modular Monolith)

```
Sutra SaaS
├── app/Modules/
│   ├── Shared/     # Core Models (User, Shop), Auth, Registration
│   ├── Inventory/  # Products, Stock, Inventory Logs
│   ├── Sales/      # Orders, Customers
│   ├── Finance/    # Transaction Logs
│   ├── Reporting/  # Dashboards, Daily Summaries
│   ├── Pos/        # Point of Sale logic
│   └── Discount/   # Discounts & Promotions
```

---

## File Locations Reference

| Type | Location |
|------|----------|
| Controllers | `app/Modules/{Module}/Http/Controllers/` |
| Services | `app/Modules/{Module}/Application/Services/` |
| Repository Interfaces | `app/Modules/{Module}/Infrastructure/Repositories/Interfaces/` |
| Repository Implementations | `app/Modules/{Module}/Infrastructure/Repositories/Eloquent/` |
| Models | `app/Modules/{Module}/Domain/Models/` |
| FormRequests | `app/Modules/{Module}/Http/Requests/` |
| Migrations | `app/Modules/{Module}/Database/Migrations/` |
| Routes | `app/Modules/{Module}/Routes/` |
| Enums | `app/Modules/{Module}/Domain/Enums/` or `app/Enums/` |

---

## Code Patterns

### 1. Request Class
**Location:** `app/Modules/{Module}/Http/Requests/`
```php
class StoreProductRequest extends FormRequest {
    public function rules(): array {
        return [
            'name' => ['required', 'string'],
            'price' => ['required', 'numeric'],
        ];
    }
}
```

### 2. Repository Interface
**Location:** `app/Modules/{Module}/Infrastructure/Repositories/Interfaces/`
```php
interface ProductRepositoryInterface {
    public function create(array $data): Product;
}
```

### 3. Service Class
**Location:** `app/Modules/{Module}/Application/Services/`
```php
final class ProductService {
    public function __construct(private readonly ProductRepositoryInterface $repo) {}
    public function create(array $data): Product {
        return $this->repo->create($data);
    }
}
```

---

## Adding a New Feature (Workflow)
1.  **Migration**: Create in `app/Modules/{Module}/Database/Migrations/`.
2.  **Model**: Create in `app/Modules/{Module}/Domain/Models/`. Use `HasUuid` and `MultiTenant`.
3.  **Repository**: Create Interface and Implementation in `Infrastructure/Repositories/`.
4.  **Binding**: Bind interface to implementation in the module's `ServiceProvider`.
5.  **Service**: Implement business logic in `Application/Services/`.
6.  **Controller**: Create in `Http/Controllers/`. Inject the service.
7.  **Routes**: Register in `Routes/web.php`.

---

## Useful Commands
```bash
# Clear all caches
php artisan optimize:clear

# Run migrations (including modules)
php artisan migrate

# Seed database
php artisan db:seed

# Development server
npm run dev
```

---

## Architecture Rules
*   **Encapsulation**: Keep module logic within its folder.
*   **Decoupling**: Use Events or Shared services for cross-module communication.
*   **PSR-12**: Strictly follow PHP coding standards.
*   **Tenancy**: Always use the `MultiTenant` trait for shop-specific data.
