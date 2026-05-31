# 🛠️ Initial Database Migrations (Sutra SaaS)

This file contains the core database structure for our project. Every table uses **UUID** for an offline-ready foundation and follows a **Multi-tenant** logic where every record is tied to a specific shop.

Migrations are now decentralized and live within their respective modules.

---

## 1. Shared Module (`app/Modules/Shared/Database/Migrations`)
Core tables required for system operation.
*   **Shops**: `shops` (Tenancy root)
*   **Users**: `users` (Authentication)
*   **Plans**: `plans` (SaaS tiers)
*   **Price Logs**: `plan_price_logs`, `module_price_logs` (Audit trails)
*   **Announcements**: `announcements` (System broadcasts)

## 2. Inventory Module (`app/Modules/Inventory/Database/Migrations`)
*   **Products**: `products` (Medicine & Retail items)
*   **Inventory Logs**: `inventory_logs` (Stock movement history)

## 3. Sales Module (`app/Modules/Sales/Database/Migrations`)
*   **Customers**: `customers` (Bakir Khata / CRM)
*   **Sales**: `sales` (POS orders)
*   **Dealers**: `dealers` (B2B dealer profiles — credit, tier, territory)

## 4. Finance Module (`app/Modules/Finance/Database/Migrations`)
*   **Transaction Logs**: `transaction_logs` (Cash flow ledger)
*   **Dealer Ledgers**: `dealer_ledgers` (Per-dealer credit/debit audit trail)

## 5. Reporting Module (`app/Modules/Reporting/Database/Migrations`)
*   **Daily Summaries**: `daily_summaries` (Pre-calculated analytics)

---

## 6. Discount Module (`app/Modules/Discount/Database/Migrations`)
*   **Discounts**: `discounts` (Legacy flat/percentage promotions)
*   **Discountables**: `discountables` (Polymorphic discount assignments)
*   **Discount Rules**: `discount_rules` (Slab-based discount tiers)

---

## 💻 Migration Standards
*   **UUID**: Always include `$table->uuid('uuid')->unique();`.
*   **Tenancy**: Always include `$table->foreignId('shop_id')->constrained()->cascadeOnDelete();` for tenant-scoped data.
*   **Soft Deletes**: Use when data persistence is critical for auditing.
*   **Idempotency**: Ledger/financial tables should include `idempotency_key` (unique) to prevent duplicate entries on queue retries.
*   **JSONB**: Use for industry-specific dynamic data (e.g., pharmacy drug specs in products).
