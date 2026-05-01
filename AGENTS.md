# Sutra SaaS - AI Agent Guidelines

## Architecture Overview
Sutra is a **multi-tenant Laravel monolith** with modular structure. Each tenant operates via subdomains (e.g., `shop.sutra.localhost`). Core layers: Request → Controller → Service → Repository → Model. Business logic resides in Services; data access via Repositories with interfaces.

## Key Conventions
- **Tenancy**: Use `Multitenant` trait on models for automatic `shop_id` scoping. Access current shop via `app(TenantManager::class)->getTenant()`.
- **Modules**: Feature modules (Inventory, POS, Finance, Reporting) in `app/Modules/`. Each has Application/Domain/Infrastructure/Http layers.
- **Validation**: All inputs via Request classes (e.g., `StoreUserRequest`). Use enums for status fields (e.g., `ActiveStatus::ACTIVE`).
- **Dependencies**: Inject services/repositories via constructor. Bind interfaces in `AppServiceProvider`.
- **Traits**: `HasUuid` for auto-generated UUIDs; `MultiTenant` for shop scoping.
- **Frontend**: React + Inertia.js. Pages in `resources/js/Pages`; components in `resources/js/Components`. Use `Ziggy` for route helpers.
- **Styling**: Tailwind CSS with custom Sutra colors (`sutra-primary`, `sutra-secondary`, `sutra-accent`).

## Development Workflow
- **Setup**: Run `composer run setup` (installs deps, migrates, builds assets).
- **Dev Server**: `composer run dev` (starts Laravel, queue worker, Vite concurrently).
- **Testing**: `composer run test` or `php artisan test`. Unit tests mock repositories; feature tests hit endpoints.
- **Migrations**: Use `php artisan make:migration` with `--create` or `--table`.
- **Modules**: Create new features in appropriate module (e.g., product CRUD in Inventory). Routes in module's `Routes/` folder.

## Code Patterns
- **Controllers**: Inject services; call `$service->method($request->validated())`; return `back()->with('success')` or Inertia renders.
- **Services**: Handle business logic; return data (not responses); use DB transactions for multi-step ops.
- **Repositories**: Implement interfaces; standard CRUD methods; use Eloquent with scopes.
- **Models**: Use `HasFactory`, `HasUuid`, `MultiTenant`; cast enums (e.g., `protected $casts = ['status' => ActiveStatus::class]`).
- **Enums**: Define in `app/Enums/`; use for type-safe values like `BusinessType::RETAIL`.
- **Routes**: Admin routes on main domain with `role:super-admin` middleware; tenant routes on subdomains.

## Examples
- **Create User**: `StoreUserRequest` validates; `UserController` calls `UserService::createUser()`; `UserRepository` does `User::create()`.
- **Tenant Context**: In services, check `$user->shop_id === auth()->user()->shop_id` for authorization.
- **Events**: Dispatch for cross-module communication (e.g., sale completion triggers inventory/finance updates).
- **Queues**: Use for background tasks like report generation or notifications.

## File Locations
- Controllers: `app/Http/Controllers/Admin/` or `Tenant/`
- Services: `app/Services/`
- Repositories: `app/Repositories/Interfaces/` and `Eloquent/`
- Models: `app/Models/`
- Requests: `app/Http/Requests/Admin/` or `Tenant/`
- Enums: `app/Enums/`
- Traits: `app/Traits/`
- Frontend: `resources/js/Pages/` and `Components/`
- Tests: `tests/Feature/` and `Unit/`

Follow PSR-12: strict types, 4-space indent, final classes, readonly properties. Reference `ARCHITECTURE.md`, `QUICK_REFERENCE.md`, `MASTER_CONTEXT.md` for details.</content>
<parameter name="filePath">D:\laragon\www\sutra-saas\AGENTS.md
