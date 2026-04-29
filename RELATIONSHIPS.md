# 🔗 Project Sutra - Database Relationships & Logic
Since this project is **Multi-tenant** and **Event-driven**, the relationships are designed so that each shop's data stays isolated and tracking is straightforward.

## 1. Shop Model (The Core Tenant)
Everything is connected to a Shop.
- `hasMany(Product)` : A shop has many products.
- `hasMany(InventoryLog)` : All stock movement history for the shop.
- `hasMany(TransactionLog)` : All income/expense or cash transactions for the shop.
- `hasMany(DailySummary)` : Daily sales and profit summaries.
- `hasMany(User)` : A shop can have multiple salesmen or managers.

## 2. Product Model
- `belongsTo(Shop)` : The product belongs to a specific shop.
- `hasMany(InventoryLog)` : Record of how many times a product's stock has moved in/out.
- **Casting:** The `metadata` field will be cast as `array` (PostgreSQL JSONB).

## 3. InventoryLog Model (Stock Tracking)
- `belongsTo(Product)` : Which product's stock was changed.
- `belongsTo(Shop)` : Which shop this change belongs to.
- **Logic:** The `type` field will contain `in`, `out`, `adjustment`, or `return`.

## 4. TransactionLog Model (Finance Tracking)
- `belongsTo(Shop)` : Which shop's money was transacted.
- `belongsTo(User)` : Which user (salesman/admin) performed the transaction.
- **Reference:** `reference_id` will be used to connect to a Sale or Purchase ID.

## 5. DailySummary Model (Reporting)
- `belongsTo(Shop)` : Which shop's report this is.
- **Unique Constraint:** `shop_id` and `report_date` together will be unique so that no more than one summary exists for the same day.

---

## 🛠️ Implementation Example (Laravel Code)
Write these relationships in your models as shown below:

### Shop.php
```php
public function products() { return $this->hasMany(Product::class); }
public function transactions() { return $this->hasMany(TransactionLog::class); }
```
