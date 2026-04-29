# 🛠️ Initial Database Migrations (Sutra SaaS)
This file contains the core database structure for our project. Every table uses **UUID** for an offline-ready foundation.
---
### 1. Shops Table
Basic shop information and module configuration.
- **Table Name:** `shops`
- **Primary:** `id` (Auto-increment) + `uuid` (Unique)
### 2. Products Table
Core product information and inventory balance.
- **Table Name:** `products`
- **Key Fields:** `sku`, `purchase_price`, `sale_price`, `stock_quantity`.
- **Flexible Data:** `metadata` (JSONB) is used for storing extra information for different industries.
### 3. Inventory Logs Table (Stock Time Machine)
For tracking every product movement (In/Out). It will update the main stock directly through internal events.
- **Table Name:** `inventory_logs`
- **Fields:** `quantity`, `type` (in/out/adj/return).
### 4. Transaction Logs Table (Finance Time Machine)
For tracking every financial transaction (Cash flow).
- **Table Name:** `transaction_logs`
- **Fields:** `amount`, `type` (credit/debit), `payment_method`.
### 5. Daily Report Summaries
This table will be used to speed up report generation.
- **Table Name:** `daily_summaries`
- **Logic:** `shop_id` + `report_date` together form a unique index.
---
## 💻 Laravel Migration Code Snippets
You can create the files using `php artisan make:migration` and use the code below:
#### ✅ Shops Schema
```php
Schema::create('shops', function (Blueprint $table) {
    $table->id();
    $table->uuid('uuid')->unique();
    $table->string('name');
    $table->string('business_type')->default('retail'); 
    $table->string('logo_path')->nullable();
    $table->jsonb('enabled_modules')->nullable(); 
    $table->string('status')->default('active');
    $table->timestamps();
});
```
