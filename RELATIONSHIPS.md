# 🔗 Project Sutra - Database Relationships & Logic

Since this project is **Multi-tenant** and **Event-driven**, the relationships are designed so that each shop's data stays isolated and tracking is straightforward.

## 1. Shop Model (The Core Tenant)
Everything is connected to a Shop.
- `hasMany(Product)` : A shop has many products.
- `hasMany(Customer)` : A shop manages its own customer base (Bakir Khata).
- `hasMany(InventoryLog)` : All stock movement history for the shop.
- `hasMany(TransactionLog)` : All income/expense or cash transactions for the shop.
- `hasMany(DailySummary)` : Daily sales and profit summaries.
- `hasMany(User)` : A shop can have multiple salesmen or managers.

## 2. Customer Model (Bakir Khata / CRM)
- `belongsTo(Shop)` : Customer belongs to a specific tenant.
- `hasMany(Sale)` : Tracking all sales made to this customer.
- `hasMany(TransactionLog)` : History of payments and credit adjustments.
- **Credit Logic:** `credit_limit` and `current_balance` fields will manage how much "Baki" a customer can take.

## 3. Product Model (Inventory & Pharma)
- `belongsTo(Shop)` : The product belongs to a specific shop.
- `hasMany(InventoryLog)` : Record of stock movements.
- **Pharma Sync:** Instead of a hard relationship, the `dgda_code` links the product to a global medicine library metadata.
- **Casting:** The `metadata` field will be cast as `array` (PostgreSQL JSONB).

## 4. Sale Model (POS & Credit Transactions)
- `belongsTo(Shop)` : Sale belongs to a specific tenant.
- `belongsTo(Customer)` : Optional for guest checkout, but required for credit/partial sales.
- `hasMany(Reminder)` : Automated WhatsApp/SMS reminders for due payments.
- **Logic:** `due_amount` and `status` (`paid`, `partial`, `credit`) track the payment health.

## 5. InventoryLog Model (Stock Tracking)
- `belongsTo(Product)` : Which product's stock was changed.
- `belongsTo(Shop)` : Which shop this change belongs to.
- **Logic:** The `type` field will contain `in`, `out`, `adjustment`, or `return`.

## 6. TransactionLog Model (Finance Tracking)
- `belongsTo(Shop)` : Which shop's money was transacted.
- `belongsTo(User)` : Which user performed the transaction.
- **Reference:** `reference_id` will be used to connect to a Sale, Purchase, or Customer Payment ID.

## 7. Reminder Model (Automated Notifications)
- `belongsTo(Sale)` : Which specific credit sale this reminder is for.
- `belongsTo(Shop)` : The shop initiating the notification.
- **Automation:** Scheduled background jobs will trigger based on `scheduled_at`.

---

## 🛠️ Implementation Example (Laravel Code)

### Shop.php
```php
public function products() { return $this->hasMany(Product::class); }
public function customers() { return $this->hasMany(Customer::class); }
public function transactions() { return $this->hasMany(TransactionLog::class); }
