# Sutra SaaS Architecture Guide

## Overview

Sutra SaaS is a multi-tenant SaaS platform built with Laravel 13, React, and Inertia.js. The application follows a **Modular Monolith architecture** with a clear three-layer pattern within each module:

1.  **Request Layer** - Input validation using dedicated Request classes
2.  **Service Layer** - Business logic and domain operations
3.  **Repository Layer** - Data access and persistence
4.  **Model Layer** - Eloquent models representing database entities

---

## Directory Structure (Modular Monolith)

The application is divided into self-contained modules located in `app/Modules/`.

```
app/
├── Http/
│   ├── Middleware/
│   └── Controller.php (Base Controller)
├── Support/     # Core infrastructure (TenantManager, etc.)
├── Enums/       # Global Enums
├── Traits/      # Global Traits
├── Providers/   # Global Service Providers
└── Modules/
    ├── Shared/     # Core models (User, Shop, Plan) and Shared Services
    ├── Inventory/  # Products and Stock management
    ├── Sales/      # Orders and Customers
    ├── Finance/    # Transactions and Accounting
    ├── Reporting/  # Dashboards and Analytics
    ├── Pos/        # Point of Sale interface
    └── Discount/   # Promotion engine
```

### Internal Module Structure
Each module follows a strictly defined internal layout:
```
app/Modules/Inventory/
├── Application/
│   └── Services/          # Business Logic
├── Database/
│   └── Migrations/        # Module-specific tables
├── Domain/
│   ├── Models/            # Eloquent entities
│   └── Enums/             # Module-specific enums
├── Http/
│   ├── Controllers/       # API/Web endpoints
│   └── Requests/          # Validation logic
├── Infrastructure/
│   ├── Repositories/      # Repository Pattern
│   │   ├── Eloquent/      # Implementations
│   │   └── Interfaces/    # Contracts
│   └── Traits/            # Module-specific traits
├── Providers/             # Module Service Provider
├── Routes/                # Web/API routes
└── Tests/                 # Unit/Feature tests
```

---

## Layer Responsibilities

### Request Layer
**Location:** `app/Modules/{Module}/Http/Requests/`
**Responsibility:** Validate input data and authorize requests.

### Controller Layer
**Location:** `app/Modules/{Module}/Http/Controllers/`
**Responsibility:** Coordinate HTTP requests and responses. Thin layer; no business logic.

### Service Layer
**Location:** `app/Modules/{Module}/Application/Services/`
**Responsibility:** Encapsulate business logic and domain operations. Orchestrates repositories.

### Repository Layer
**Location:** `app/Modules/{Module}/Infrastructure/Repositories/`
**Responsibility:** Provide an interface for data access. Decouples business logic from Eloquent.

### Model Layer
**Location:** `app/Modules/{Module}/Domain/Models/`
**Responsibility:** Define database entities and relationships.

---

## Multi-Tenancy
*   **Tenant Scoping**: Handled via the `MultiTenant` trait on models.
*   **Tenant Resolution**: Managed by `App\Support\TenantManager`.
*   **Team Context**: Uses Spatie Permission with team IDs scoped to `shop_id`.

---

## Module Registration & Discovery
Modules are automatically discovered and registered by the `App\Providers\ModuleServiceProvider`. This provider scans the `app/Modules` directory and registers any `ServiceProvider` found within each module's `Providers` directory.

---

## Implementation Workflow (Checklist)
1.  Define **Migration** in `Database/Migrations/`.
2.  Define **Model** in `Domain/Models/`.
3.  Define **Repository Interface** in `Infrastructure/Repositories/Interfaces/`.
4.  Implement **Repository** in `Infrastructure/Repositories/Eloquent/`.
5.  Create **Request Class** in `Http/Requests/`.
6.  Create **Service Class** in `Application/Services/`.
7.  Create **Controller** in `Http/Controllers/`.
8.  Bind Interface to Implementation in the module's **ServiceProvider**.
9.  Register **Routes** in `Routes/web.php` or `Routes/api.php`.
