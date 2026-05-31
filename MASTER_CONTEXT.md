# 🚀 Project Sutra (সূত্র) - Master Context

## 1. Vision & Overview
Sutra is a **Multi-Tenant, Modular Monolith** B2B Distribution & Dealer Management System purpose-built for Bangladeshi wholesale distributors, manufacturers, and dealer networks. From a single codebase with subdomain-based tenancy, Sutra manages the complete distribution lifecycle — from inventory procurement to dealer onboarding, slab-based pricing, order fulfillment, and automated commission settlement.

> **Core Goal:** Managing wholesale distribution, dealer networks, and automated commissions at scale.

## 2. Core Architecture Philosophy
*   **Modular Monolith**: Every business capability is a standalone module in `app/Modules/`.
*   **Standalone Modules**: Each module contains its own Models, Migrations, Repositories, Services, and Controllers.
*   **Event-Driven**: Modules communicate via Events to maintain decoupling. High-volume operations (ledger entries, commission calculations, settlement) are processed asynchronously through the **Event Engine** (see `docs/event_engine.md`).
*   **Multi-Tenancy**: Automated via the `MultiTenant` trait and `TenantManager`. Each distributor/manufacturer operates on its own subdomain.
*   **Offline-Ready**: UUIDs are used for all entities to support future offline sync for field sales agents.
*   **Ledger-First**: All financial mutations flow through the Finance module's double-entry ledger to ensure auditability and reconciliation at B2B volume.

## 3. Technical Stack
*   **Backend**: Laravel 13, PHP 8.5, PostgreSQL (JSONB support).
*   **Frontend**: React, Inertia.js, Tailwind CSS, Shadcn UI.
*   **State/Cache**: Redis for caching, queue management, and rate-limiting high-frequency ledger writes.

## 4. Business Domain — Key Concepts

### 4.1 Dealer Profiles
A **Dealer** is a downstream business entity (retailer, sub-distributor, or institutional buyer) managed by the tenant (distributor). Each dealer profile stores:
*   **Identity**: Business name, trade license, TIN, contact info.
*   **Credit Terms**: Credit limit, payment terms (net-15, net-30, etc.), outstanding balance.
*   **Territory**: Assigned sales territory or zone for route-based distribution.
*   **Tier/Classification**: Gold, Silver, Bronze — determines slab eligibility.
*   **Relationship**: `belongsTo(Shop)`, linked via the `MultiTenant` trait.

### 4.2 Slab-Based Discounts
Slab discounts replace flat promotional discounts. Pricing tiers are volume-driven:
*   **Structure**: Configurable slabs per product/category (e.g., 1–100 units → 5%, 101–500 → 8%, 500+ → 12%).
*   **Scope**: Can be applied globally, per-dealer-tier, per-territory, or per-product-category.
*   **Calculation**: Evaluated at order time by the `Discount` module's `SlabDiscountService`.
*   **Audit**: Every slab application is logged for pricing transparency and dispute resolution.

### 4.3 Periodical Commissions
Automated commission payouts for dealers, sales agents, and sub-distributors:
*   **Periods**: Monthly, quarterly, or custom date ranges.
*   **Basis**: Calculated on confirmed & settled orders (not just placed orders).
*   **Rules**: Configurable commission rates per dealer tier, product category, or territory.
*   **Settlement Flow**: 
    1. `CommissionCalculationJob` runs at period close.
    2. Generates `CommissionStatement` entries per dealer/agent.
    3. Dispatches `CommissionSettled` event → Finance module creates ledger entries.
    4. Optional: Auto-generates payment instructions for bank/bKash settlement.
*   **Reconciliation**: Full audit trail linking commission amounts back to source orders.

## 5. Directory Structure
```
app/
├── Http/        # Base infrastructure
├── Support/     # TenantManager, common utilities
├── Modules/     # Feature Modules
│   ├── Shared/       # User, Shop, Auth, core traits/enums
│   ├── Inventory/    # Products, Stock, Warehouse Management
│   ├── Sales/        # Orders, Dealers, Territories
│   ├── Finance/      # Ledger, Settlements, Payment Tracking
│   ├── Reporting/    # Dashboards, Analytics, Commission Reports
│   ├── Pos/          # Point of Sale (retained for hybrid B2B/B2C)
│   ├── Discount/     # Slab-Based Discounts, Promotional Rules
│   └── Distribution/ # Route Planning, Delivery Tracking (future)
└── Providers/   # ModuleServiceProvider (Auto-discovery)
```

## 6. Module Layering (Per Module)
*   **Application**: Services (Business Logic), Jobs (Async processing).
*   **Domain**: Models, Enums, Events, Value Objects.
*   **Infrastructure**: Repository Implementations & Interfaces.
*   **Http**: Controllers & Form Requests.
*   **Database**: Migrations, Seeders, Factories.

## 7. Communication Rules
*   **No Direct Model Access**: Services must use Repository Interfaces.
*   **Cross-Module**: Use Events or Shared Services. Avoid direct dependencies between feature modules.
*   **Global Access**: Shared traits like `HasUuid` and `MultiTenant` ensure project-wide consistency.
*   **High-Volume Ledger Operations**: All financial mutations (order payments, commission settlements, dealer credit adjustments) are dispatched as queued events processed by the Event Engine to avoid blocking the request cycle.

## 8. Event Engine — Distribution & Settlement Flows

The Event Engine (`docs/event_engine.md`) orchestrates the following B2B-specific flows:

### 8.1 Order → Fulfillment → Ledger
```
Dealer Order Placed → OrderCreated Event
  → Validate Dealer Credit Limit
  → Apply Slab Discount
  → Reserve Stock (Inventory Module)
  → Create Ledger Entry (Finance Module)
  → Dispatch Fulfillment Notification
```

### 8.2 Payment → Settlement → Commission
```
Payment Received → PaymentRecorded Event
  → Update Dealer Outstanding Balance
  → Settle Ledger Entry (Finance Module)
  → Check Commission Eligibility
  → Queue for Periodical Commission Calculation
```

### 8.3 Commission Period Close
```
Scheduled Job → CommissionPeriodClosed Event
  → Calculate commissions per dealer/agent
  → Generate CommissionStatement records
  → Create Settlement Ledger Entries
  → Notify dealers/agents of payout
```

## 9. New Entity Summary

| Entity | Module | Key Fields |
|--------|--------|------------|
| `Dealer` | Sales | `shop_id`, `name`, `tier`, `credit_limit`, `territory_id`, `payment_terms`, `outstanding_balance` |
| `Territory` | Sales | `shop_id`, `name`, `region`, `assigned_agent_id` |
| `SlabDiscount` | Discount | `shop_id`, `product_id`/`category_id`, `min_qty`, `max_qty`, `discount_percent`, `dealer_tier` |
| `CommissionRule` | Finance | `shop_id`, `dealer_tier`, `product_category`, `rate_percent`, `period_type` |
| `CommissionStatement` | Finance | `shop_id`, `dealer_id`/`agent_id`, `period_start`, `period_end`, `total_amount`, `status` |
| `LedgerEntry` | Finance | `shop_id`, `account_type`, `debit`, `credit`, `reference_type`, `reference_id`, `narration` |

## 10. Technical Scope — B2B Focus Areas

### 10.1 High-Volume Ledger Transactions
*   **Double-Entry Bookkeeping**: Every financial event creates balanced debit/credit entries.
*   **Batch Processing**: Ledger writes for bulk orders and commission settlements are batched and queued via Redis.
*   **Idempotency**: All ledger operations use idempotency keys to prevent duplicate entries during retries.
*   **JSONB Metadata**: PostgreSQL JSONB columns store flexible transaction metadata (source order, slab applied, commission rule reference).

### 10.2 Automated Settlement
*   **Settlement Engine**: A scheduled service that matches payments to outstanding invoices using FIFO allocation.
*   **Auto-Reconciliation**: Unmatched payments are flagged for manual review.
*   **Commission Payouts**: Processed via the Event Engine at configurable intervals (monthly/quarterly).

### 10.3 Dealer Credit Management
*   **Credit Limit Enforcement**: Orders exceeding a dealer's available credit are held for approval.
*   **Aging Reports**: Automated tracking of receivables by age bucket (0–30, 31–60, 61–90, 90+ days).
*   **Credit Score**: Internal scoring based on payment history to auto-adjust dealer tiers.

## 11. Roadmap
*   **Phase 1**: B2B Distribution MVP — Dealer Profiles, Slab Discounts, Order Management, Ledger (Current).
*   **Phase 2**: Commission Engine — Automated periodical commissions, settlement, and payout reports.
*   **Phase 3**: Distribution Logistics — Route planning, delivery tracking, field agent mobile app.
*   **Phase 4**: Advanced Analytics — Dealer performance dashboards, territory optimization, demand forecasting.
*   **Phase 5**: Offline-First Sync — Field agent offline order capture with conflict resolution.
