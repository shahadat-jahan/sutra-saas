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

## 4. Finance Module (`app/Modules/Finance/Database/Migrations`)
*   **Transaction Logs**: `transaction_logs` (Cash flow ledger)

## 5. Reporting Module (`app/Modules/Reporting/Database/Migrations`)
*   **Daily Summaries**: `daily_summaries` (Pre-calculated analytics)

---

## 💻 Migration Standards
*   **UUID**: Always include `$table->uuid('uuid')->unique();`.
*   **Tenancy**: Always include `$table->foreignId('shop_id')->constrained()->onDelete('cascade');` for tenant-scoped data.
*   **Soft Deletes**: Use when data persistence is critical for auditing.
*   **JSONB**: Use for industry-specific dynamic data (e.g., pharmacy drug specs in products).
