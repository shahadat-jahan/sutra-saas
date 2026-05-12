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
│   ├── Requests/ 
│   └── Middleware/ 
├── Support/ # Core infrastructure logic 
├── Enums/   # Global enums 
├── Traits/  # Global traits 
├── Providers/ 
└── Modules/ # Everything else lives in modules 
    ├── Finance/ 
    ├── Inventory/ 
    ├── Pos/ 
    ├── Reporting/ 
    ├── Sales/ 
    ├── Shared/ # Core models (User, Shop, etc.) 
    └── Discount/ 
``` 

### Module Internal Structure 
Each module under `app/Modules/` follows this layout: 
``` 
app/Modules/Inventory/ 
├── Application/ 
│   └── Services/ 
├── Database/ 
│   └── Migrations/ 
├── Domain/ 
│   ├── Models/ 
│   └── Enums/ 
├── Http/ 
│   ├── Controllers/ 
│   └── Requests/ 
├── Infrastructure/ 
│   └── Repositories/ 
│       ├── Eloquent/ 
│       └── Interfaces/ 
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
- Models must use `HasUuid` and `MultiTenant` (if scoped).
- Location: `app/Modules/{Module}/Domain/Models/`

### Enums 
- Global: `app/Enums/`
- Module-specific: `app/Modules/{Module}/Domain/Enums/`

### Traits 
- Global: `app/Traits/`
- Module-specific: `app/Modules/{Module}/Infrastructure/Traits/`

### Validation 
- Always use Form Request classes.
- Location: `app/Modules/{Module}/Http/Requests/`

### Dependency Injection 
- Always inject **interfaces** from the module's repository layer.

--- 

## Adding a New Feature (Checklist) 
Follow this order every time — do not skip steps: 
1. **FormRequest** → `app/Modules/{Module}/Http/Requests/`
2. **Repository Interface** → `app/Modules/{Module}/Infrastructure/Repositories/Interfaces/`
3. **Repository Implementation** → `app/Modules/{Module}/Infrastructure/Repositories/Eloquent/`
4. **Bind in ModuleServiceProvider** → `$this->app->bind(Interface::class, Implementation::class)` 
5. **Service** → `app/Modules/{Module}/Application/Services/`
6. **Controller** → `app/Modules/{Module}/Http/Controllers/`
7. **Routes** → `app/Modules/{Module}/Routes/`
8. **Frontend Page** → `resources/js/Pages/` 
9. **Tests** → `app/Modules/{Module}/Tests/`

--- 

## Database 
- **PostgreSQL** — do not use MySQL-specific syntax.
- Migrations are stored within modules: `app/Modules/{Module}/Database/Migrations/`

--- 

## File Locations Reference 
| Type | Location | 
|------|----------| 
| Controllers | `app/Modules/{Module}/Http/Controllers/` 
| Services | `app/Modules/{Module}/Application/Services/` 
| Repository Interfaces | `app/Modules/{Module}/Infrastructure/Repositories/Interfaces/` 
| Repository Implementations | `app/Modules/{Module}/Infrastructure/Repositories/Eloquent/` 
| Models | `app/Modules/{Module}/Domain/Models/` 
| FormRequests | `app/Modules/{Module}/Http/Requests/` 
| Enums | `app/Modules/{Module}/Domain/Enums/` or `app/Enums/`
| Traits | `app/Modules/{Module}/Infrastructure/Traits/` or `app/Traits/`
| Frontend Pages | `resources/js/Pages/` 
| Frontend Components | `resources/js/Components/` 
| Feature Tests | `app/Modules/{Module}/Tests/Feature/` 
| Unit Tests | `app/Modules/{Module}/Tests/Unit/` 
| Module Routes | `app/Modules/{Module}/Routes/` 

--- 

## Anti-Patterns — Never Do These 
- **Direct Model Access from Controller**: Always go through a Service.
- **Cross-Module Model Usage**: Use events or shared interfaces to decouple modules.

---

## PSR-12 & Code Style
All files must follow PSR-12 strictly.
Use `./vendor/bin/pint` to fix code style before committing any code.

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
**Cache Service**: `App\Modules\Shared\Application\Services\CacheService`.

--- 

## Final Notes 
- **Auto-Discovery**: Modules are automatically discovered via `App\Providers\ModuleServiceProvider`.
