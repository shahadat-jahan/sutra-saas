# 🚀 Project Sutra (সূত্র) - Master Context

## 1. Vision & Overview
Sutra is a **Multi-Tenant, Modular Monolith** Business Operating System. It is built to handle different types of businesses (Retail, Pharmacy, etc.) from a single codebase using subdomain-based tenancy.

## 2. Core Architecture Philosophy
*   **Modular Monolith**: Every business capability is a standalone module in `app/Modules/`.
*   **Standalone Modules**: Each module contains its own Models, Migrations, Repositories, Services, and Controllers.
*   **Event-Driven**: Modules communicate via Events to maintain decoupling.
*   **Multi-Tenancy**: Automated via the `MultiTenant` trait and `TenantManager`.
*   **Offline-Ready**: UUIDs are used for all entities to support future offline sync.

## 3. Technical Stack
*   **Backend**: Laravel 13, PHP 8.5, PostgreSQL (JSONB support).
*   **Frontend**: React, Inertia.js, Tailwind CSS, Shadcn UI.
*   **State/Cache**: Redis for caching and queue management.

## 4. Directory Structure
```
app/
├── Http/        # Base infrastructure
├── Support/     # TenantManager, common utilities
├── Modules/     # Feature Modules
│   ├── Shared/     # User, Shop, Auth, core traits/enums
│   ├── Inventory/  # Products & Stock
│   ├── Sales/      # Orders & Customers
│   ├── Finance/    # Accounting & Ledgers
│   ├── Reporting/  # Dashboards & Analytics
│   ├── Pos/        # Point of Sale
│   └── Discount/   # Promotions
└── Providers/   # ModuleServiceProvider (Auto-discovery)
```

## 5. Module Layering (Per Module)
*   **Application**: Services (Business Logic).
*   **Domain**: Models, Enums, Events.
*   **Infrastructure**: Repository Implementations & Interfaces.
*   **Http**: Controllers & Form Requests.
*   **Database**: Migrations, Seeders, Factories.

## 6. Communication Rules
*   **No Direct Model Access**: Services should use Repository Interfaces.
*   **Cross-Module**: Use Events or Shared Services. Avoid direct dependencies between feature modules.
*   **Global Access**: Shared traits like `HasUuid` and `MultiTenant` ensure project-wide consistency.

## 7. Roadmap
*   **Phase 1**: Retail & Pharmacy MVP (Current).
*   **Phase 2**: Restaurant & Recipe Management.
*   **Phase 3**: Production & Assembly Line.
*   **Phase 4**: Offline-First Sync.
