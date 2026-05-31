# 🔗 Project Sutra - Database Relationships & Logic

Sutra SaaS uses a **Modular Monolith** architecture where relationships often span across modules. 

## 1. Shop Model (Tenant Core - Shared Module)
*   **Inventory**: `hasMany(Product)`, `hasMany(InventoryLog)`
*   **Sales**: `hasMany(Customer)`, `hasMany(Sale)`
*   **Finance**: `hasMany(TransactionLog)`
*   **Reporting**: `hasMany(DailySummary)`
*   **Users**: `hasMany(User)`

## 2. Customer Model (Sales Module)
*   `belongsTo(Shop)`
*   `hasMany(Sale)`
*   `hasMany(TransactionLog)` (Finance Module)
*   **Logic**: `credit_limit` and `current_balance` manage "Bakir Khata".

## 3. Product Model (Inventory Module)
*   `belongsTo(Shop)`
*   `hasMany(InventoryLog)`
*   **Pharma Sync**: Linked via `dgda_code`.

## 4. Sale Model (Sales Module)
*   `belongsTo(Shop)`
*   `belongsTo(Customer)`
*   `hasMany(Reminder)` (Shared/Marketing)
*   **Logic**: Tracks `due_amount` and `status` (paid, partial, credit).

## 5. InventoryLog Model (Inventory Module)
*   `belongsTo(Product)`
*   `belongsTo(Shop)`
*   **Types**: `in`, `out`, `adjustment`, `return`.

## 6. TransactionLog Model (Finance Module)
*   `belongsTo(Shop)`
*   `belongsTo(User)` (Shared Module)
*   `belongsTo(Customer)` (Sales Module)
*   **Reference**: `reference_id` connects to Sale, Purchase, or Payment IDs.

## 7. Reminder Model (Shared Module)
*   `belongsTo(Sale)` (Sales Module)
*   `belongsTo(Shop)`

---

## 🛠️ Cross-Module Relationships
Since modules are standalone, cross-module relationships should ideally be handled via:
1.  **Interfaces**: Injecting repository interfaces from other modules.
2.  **Events**: Dispatched when data changes in one module to notify another (e.g., `SaleCreated` triggers `InventoryReduction` and `TransactionLogCreation`).
3.  **Global Scoping**: The `MultiTenant` trait ensures all data is automatically filtered by the current `shop_id`.
