# 🛠️ Initial Database Migrations (Sutra SaaS)

এই ফাইলটিতে আমাদের প্রজেক্টের কোর ডাটাবেজ স্ট্রাকচার দেওয়া হলো। প্রতিটি টেবিলে **UUID** ব্যবহার করা হয়েছে অফলাইন-রেডি ফাউন্ডেশনের জন্য।

---

### 1. Shops Table
দোকানের বেসিক তথ্য এবং মডিউল কনফিগারেশন।
- **Table Name:** `shops`
- **Primary:** `id` (Auto-increment) + `uuid` (Unique)

### 2. Products Table
পণ্যের মূল তথ্য এবং ইনভেন্টরি ব্যালেন্স।
- **Table Name:** `products`
- **Key Fields:** `sku`, `purchase_price`, `sale_price`, `stock_quantity`.
- **Flexible Data:** `metadata` (JSONB) ব্যবহার করা হয়েছে বিভিন্ন ইন্ডাস্ট্রির অতিরিক্ত তথ্যের জন্য।

### 3. Inventory Logs Table (Stock Time Machine)
পণ্যের প্রতিটি মুভমেন্ট (In/Out) ট্র্যাক করার জন্য। এটি সরাসরি মেইন স্টকের সাথে ইন্টারনাল ইভেন্টের মাধ্যমে আপডেট হবে।
- **Table Name:** `inventory_logs`
- **Fields:** `quantity`, `type` (in/out/adj/return).

### 4. Transaction Logs Table (Finance Time Machine)
টাকার প্রতিটি লেনদেন (Cash flow) ট্র্যাক করার জন্য।
- **Table Name:** `transaction_logs`
- **Fields:** `amount`, `type` (credit/debit), `payment_method`.

### 5. Daily Report Summaries
রিপোর্ট জেনারেট করার স্পিড বাড়াতে এই টেবিলটি ব্যবহার করা হবে।
- **Table Name:** `daily_summaries`
- **Logic:** `shop_id` + `report_date` মিলে ইউনিক ইনডেক্স।

---

## 💻 Laravel Migration Code Snippets

আপনি `php artisan make:migration` দিয়ে ফাইলগুলো তৈরি করে নিচের কোডগুলো ব্যবহার করতে পারেন:

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
