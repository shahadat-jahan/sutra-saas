# 🔗 Project Sutra - Database Relationships & Logic

এই প্রজেক্টটি **Multi-tenant** এবং **Event-driven** হওয়ায় রিলেশনশিপগুলো এমনভাবে ডিজাইন করা হয়েছে যাতে প্রতিটি দোকানের ডাটা আলাদা থাকে এবং ট্র্যাকিং সহজ হয়।

## 1. Shop Model (The Core Tenant)
সবকিছু একটি দোকানের (Shop) সাথে যুক্ত।
- `hasMany(Product)` : একটি দোকানের অনেক প্রোডাক্ট থাকে।
- `hasMany(InventoryLog)` : দোকানের সব স্টক মুভমেন্ট হিস্ট্রি।
- `hasMany(TransactionLog)` : দোকানের সব আয়-ব্যয় বা ক্যাশ ট্রানজেকশন।
- `hasMany(DailySummary)` : প্রতিদিনের বিক্রয় ও লাভের সামারি।
- `hasMany(User)` : দোকানে একাধিক সেলসম্যান বা ম্যানেজার থাকতে পারে।

## 2. Product Model
- `belongsTo(Shop)` : প্রোডাক্টটি একটি নির্দিষ্ট দোকানের।
- `hasMany(InventoryLog)` : একটি প্রোডাক্টের স্টক কতবার ইন/আউট হয়েছে তার রেকর্ড।
- **Casting:** `metadata` ফিল্ডটি `array` হিসেবে কাস্ট করা হবে (PostgreSQL JSONB)।

## 3. InventoryLog Model (Stock Tracking)
- `belongsTo(Product)` : কোন প্রোডাক্টের স্টক পরিবর্তন হয়েছে।
- `belongsTo(Shop)` : কোন দোকানের আন্ডারে এই পরিবর্তন।
- **Logic:** `type` ফিল্ডে `in`, `out`, `adjustment`, বা `return` থাকবে।

## 4. TransactionLog Model (Finance Tracking)
- `belongsTo(Shop)` : কোন দোকানের টাকা লেনদেন হয়েছে।
- `belongsTo(User)` : কোন ইউজার (সেলসম্যান/এডমিন) লেনদেনটি করেছে।
- **Reference:** `reference_id` দিয়ে Sale বা Purchase আইডির সাথে কানেক্ট করা হবে।

## 5. DailySummary Model (Reporting)
- `belongsTo(Shop)` : কোন দোকানের রিপোর্ট।
- **Unique Constraint:** `shop_id` এবং `report_date` মিলে ইউনিক হবে যাতে একই দিনে একটির বেশি সামারি না থাকে।

---

## 🛠️ Implementation Example (Laravel Code)

আপনার মডেলগুলোতে এই রিলেশনশিপগুলো নিচের মতো করে লিখবেন:

### Shop.php
```php
public function products() { return $this->hasMany(Product::class); }
public function transactions() { return $this->hasMany(TransactionLog::class); }
