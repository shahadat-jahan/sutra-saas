# 🛠️ Initial Database Migrations (Sutra SaaS)

This file contains the core database structure for our project. Every table uses **UUID** for an offline-ready foundation and follows a **Multi-tenant** logic where every record is tied to a specific shop.

---

### 1. Shops Table
Basic shop information, subdomain configuration, and module toggles.
- **Table Name:** `shops`
- **Primary:** `id` (Auto-increment) + `uuid` (Unique)
- **Key Fields:** `slug` (for subdomains), `enabled_modules` (JSONB for toggling Pharma/Bakir Khata).

### 2. Customers Table (Bakir Khata / CRM)
Used for tracking individual customer credit, payment history, and NID info.
- **Table Name:** `customers`
- **Key Fields:** `credit_limit`, `current_balance` (Negative for debt/baki).

### 3. Products Table (Inventory & Pharma)
Core product information and inventory balance. Supports both general retail and pharmacy items.
- **Table Name:** `products`
- **Key Fields:** `generic_name`, `dgda_code`, `sku`, `purchase_price`, `sale_price`, `stock_quantity`.
- **Flexible Data:** `metadata` (JSONB) is used for industry-specific data.

### 4. Inventory Logs Table (Stock Time Machine)
For tracking every product movement (In/Out/Adjustment).
- **Table Name:** `inventory_logs`
- **Fields:** `quantity`, `type` (in/out/adj/return).

### 5. Transaction Logs Table (Finance Time Machine)
For tracking every financial transaction (Cash flow and Ledger entries).
- **Table Name:** `transaction_logs`
- **Fields:** `amount`, `type` (income/expense), `reference_id` (Link to Sale/Purchase).

### 6. Reminders Table (Automation)
Schedules and logs automated notifications (WhatsApp/SMS) for credit recovery.
- **Table Name:** `reminders`
- **Fields:** `sale_id`, `type` (whatsapp/sms), `status` (pending/sent), `scheduled_at`.

### 7. Daily Report Summaries
Speed up report generation by pre-calculating daily stats.
- **Table Name:** `daily_summaries`
- **Logic:** `shop_id` + `report_date` form a unique index.

---

## 💻 Laravel Migration Code Snippets

#### ✅ Shops Schema
```php
Schema::create('shops', function (Blueprint $table) {
    $table->id();
    $table->uuid('uuid')->unique();
    $table->string('name');
    $table->string('slug')->unique(); // For subdomain routing
    $table->string('business_type')->default('retail'); 
    $table->jsonb('enabled_modules')->nullable(); // e.g., {"pharma": true, "bakir_khata": true}
    $table->string('status')->default('active');
    $table->timestamps();
});
