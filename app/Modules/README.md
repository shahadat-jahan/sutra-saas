# Modular Monolith Structure

Each business module lives inside `app/Modules/<ModuleName>`.

Recommended structure:

```text
app/Modules/
  Shared/
    Support/
  Inventory/
    Application/
    Domain/
    Infrastructure/
    Http/
      Controllers/
      Requests/
    Providers/
    Routes/
    Database/
      Migrations/
      Seeders/
    Resources/
      js/
        Pages/
      views/
    Tests/
      Feature/
      Unit/
  Pos/
  Finance/
```

Layer guide:

- `Domain`: entities, value objects, domain services, business rules.
- `Application`: use cases, actions, DTOs, orchestration logic.
- `Infrastructure`: repository implementations, external services, persistence details.
- `Http`: controllers, form requests, API/web entry points.
- `Providers`: module-specific service providers when a module grows.
- `Routes`: module-local `web.php` and `api.php`.
- `Database`: migrations, seeders, factories dedicated to the module.
- `Resources`: Inertia pages, components, Blade views, module assets.
- `Tests`: feature and unit tests for the module.

Keep shared cross-module code inside `app/Modules/Shared`.
