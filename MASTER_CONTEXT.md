# 🚀 Project Sutra (সূত্র) - Master Context

## 1. Vision & Overview
Sutra is a **Multi-Tenant, Modular Monolith** Business Operating System. It is built to handle different types of businesses from a Single Codebase. Its core goal is to help Small and Medium Enterprises (SMEs) manage their day-to-day operations through a unified platform.

## 2. Core Architecture Philosophy
- **Modular Monolith:** Modules (Inventory, POS, Finance) will remain decoupled.
- **Event-Driven:** Laravel Events & Queues will be used for background processing.
- **Hybrid Data Model:** Relational Tables + JSONB Metadata.
- **Offline-Ready:** UUID is used in every transaction to allow offline syncing in the future.
- **Future Expansion:** Ready for B2C E-commerce storefront integration via API.

## 3. Technical Stack & Packages
### First-party (Laravel Core)
- **Auth:** `Laravel Breeze` (Inertia React Edition)
- **API Security:** `Laravel Sanctum`
- **Coding Standard:** `Laravel Pint`

### Essential Packages
- **Permissions:** `spatie/laravel-permission`
- **Images:** `intervention/image` (Logo/Product image processing)
- **Reports:** `barryvdh/laravel-dompdf` & `maatwebsite/excel`
- **UI:** `lucide-react`, `recharts`, `shadcn/ui`

## 4. Database Strategy
- **Inventory Logs:** Tracks every product movement.
- **Transaction Logs:** Tracks every financial transaction.
- **UUID Identity:** Every main table will have a unique `uuid` alongside the Primary ID.

## 5. Performance Strategy
- **Queues:** Inventory and finance updates will be processed in the background using Redis.
- **Caching:** Redis will be used for reporting data.

## 6. Roadmap
- **Phase 1: Retail & Pharmacy MVP.**
  - Integration of **DGDA Medicine Database** for automated inventory setup.
  - Implementation of **Bakir Khata** (Credit Management) with automated WhatsApp payment reminders.
- **Phase 2:** Restaurant (with Recipe Management).
- **Phase 3:** Assembly Line / Production (e.g., Easy-bike factory).
- **Phase 4:** Offline-First Sync Implementation (IndexedDB + Service Workers).

## 7. Modular Monolith Folder Structure
The project will be a Single Codebase, but divided into modules by business capability. Each module will be self-contained to make it easy to maintain, test, and extend in the future.

```text
app/
├── Modules/
│   ├── Shared/
│   │   ├── Support/
│   │   ├── Traits/
│   │   ├── Enums/
│   │   └── Services/
│   │
│   ├── Inventory/
│   │   ├── Application/
│   │   │   ├── Actions/
│   │   │   ├── DTOs/
│   │   │   └── Services/
│   │   ├── Domain/
│   │   │   ├── Models/
│   │   │   ├── Enums/
│   │   │   ├── Rules/
│   │   │   └── Events/
│   │   ├── Infrastructure/
│   │   │   ├── Repositories/
│   │   │   └── Queries/
│   │   ├── Http/
│   │   │   ├── Controllers/
│   │   │   ├── Requests/
│   │   │   └── Resources/
│   │   ├── Database/
│   │   │   ├── Migrations/
│   │   │   ├── Seeders/
│   │   │   └── Factories/
│   │   ├── Routes/
│   │   │   ├── web.php
│   │   │   └── api.php
│   │   ├── Policies/
│   │   ├── Providers/
│   │   └── Tests/
│   │       ├── Feature/
│   │       └── Unit/
│   │
│   ├── Pos/
│   ├── Finance/
│   ├── CRM/
│   ├── HRM/
│   └── Reporting/
│
├── Models/
├── Http/
├── Providers/
└── Traits/

resources/
├── js/
│   ├── Pages/
│   ├── Components/
│   ├── Layouts/
│   └── Modules/
│       ├── Inventory/
│       ├── Pos/
│       ├── Finance/
│       └── Shared/
└── views/
```

## 8. Module Layer Responsibilities
The responsibility of each layer inside every module will be clearly separated.

- **Application:** Use case orchestration, actions, DTOs, workflow services.
- **Domain:** Business rules, entities/models, enums, events, validation rules.
- **Infrastructure:** Repositories, query handlers, external integration, persistence details.
- **Http:** Controllers, Form Requests, API Resources, route-facing classes.
- **Database:** Module-specific migrations, seeders, factories.
- **Providers:** Module bootstrapping, bindings, listeners registration.
- **Policies:** Authorization rules related to the module.
- **Tests:** Module-based unit and feature tests.

## 9. Shared vs Module-Specific Code
- Cross-module reusable code will live in `app/Modules/Shared`.
- Global reusable traits such as `HasUuid` can be placed in `app/Traits`.
- Module-specific traits, enums, helpers, or services must stay inside that module.
- Shared code must never carry specific business rules.
- Inventory logic must not be written inside POS or Finance folders.

## 10. Routing Strategy
- Main application shell routes will stay in the global `routes/` folder.
- Business module routes will stay in `app/Modules/<ModuleName>/Routes/`.
- Web routes and API routes will be kept in separate files.
- Module route naming will be prefix-based.
- Example: `inventory.products.index`, `pos.sales.store`, `finance.expenses.index`

## 11. Frontend Module Strategy
- Shared UI components will live in `resources/js/Components`.
- Global layouts will live in `resources/js/Layouts`.
- Module-specific pages/components will live in `resources/js/Modules/<ModuleName>`.
- Application-level pages like Breeze/Auth/Profile can stay in global `resources/js/Pages`.
- If a module's UI grows large, its pages, partials, forms, and widgets must be kept inside that module's folder.

## 12. Naming Conventions
- Module names will be readable domain names, not singular business capabilities: `Inventory`, `Pos`, `Finance`
- Controller names: `ProductController`, `SaleController`, `ExpenseController`
- Action names: `CreateProductAction`, `RecordSaleAction`, `PostExpenseAction`
- DTO names: `CreateProductData`, `SaleData`
- Event names: `StockAdjusted`, `SaleCompleted`, `ExpenseRecorded`
- Trait names: `HasUuid`, `HasTenant`, `LogsActivity`

## 13. Module Boundary Rules
- One module must not directly use another module's internal classes if the work can be done via shared contracts or event-based communication.
- Cross-module communication is preferred via Events, Actions, Interfaces, or Shared Services.
- The Finance module must not directly mutate Inventory stock.
- When a POS sale is completed, an event will be dispatched; Inventory and Finance will consume that event.
- The Reporting module will be read-heavy, not write logic.

## 14. Initial Core Modules
- **Inventory:** Product, stock movement, batch/expiry tracking, purchase stock intake, and **DGDA Pharma Sync** (auto-fetching medicine data via API/Library).
- **POS:** Cart, discount, Sales, invoicing, and **Bakir Khata Engine** (handling credit sales limit & history).
- **Finance:** Cashbook,  transaction log, expense, payment tracking, and **Debt Collection Automation** (scheduled WhatsApp/SMS reminders).
- **Reporting:** Daily summary, profit/loss, stock report, sales analytics
- **Shared:** UUID trait, tenant context, common enums, shared support services

## 15. Implementation Guideline
- For every new business feature, first identify the module, then create the folder.
- Eloquent models are preferred to be kept in the module's `Domain/Models`.
- Complex business logic must not be written in controllers; it must be moved to the Action/Service layer.
- Reusable UUID behavior will be kept as a trait and used in all models that have a `uuid` column.
- A module-aware test structure must be maintained from the start.
- **Third-party Integrations:** External APIs (WhatsApp Gateway, SMS Gateway, DGDA Data) must be abstracted into `Shared/Services` to keep business logic decoupled.
