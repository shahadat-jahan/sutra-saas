# 🚀 Project Sutra (সূত্র) - Master Context

## 1. Vision & Overview
Sutra একটি **Multi-Tenant, Modular Monolith** বিজনেস অপারেটিং সিস্টেম। এটি একটি Single Codebase থেকে বিভিন্ন ধরণের ব্যবসা হ্যান্ডেল করার জন্য তৈরি। এর মূল লক্ষ্য হলো ছোট ও মাঝারি ব্যবসাগুলোকে (SMEs) একটি সমন্বিত প্ল্যাটফর্মের মাধ্যমে তাদের দৈনন্দিন কার্যক্রম পরিচালনা করতে সহায়তা করা।

## 2. Core Architecture Philosophy
- **Modular Monolith:** মডিউলগুলো (Inventory, POS, Finance) ডিকাপল্ড থাকবে।
- **Event-Driven:** ব্যাকগ্রাউন্ডে কাজ করার জন্য Laravel Events & Queues ব্যবহার করা হবে।
- **Hybrid Data Model:** Relational Tables + JSONB Metadata।
- **Offline-Ready:** প্রতিটি ট্রানজেকশনে UUID ব্যবহার করা হয়েছে যাতে ভবিষ্যতে অফলাইন সিঙ্কিং করা যায়।
- **Future Expansion:** Ready for B2C E-commerce storefront integration via API.

## 3. Technical Stack & Packages
### First-party (Laravel Core)
- **Auth:** `Laravel Breeze` (Inertia React Edition)
- **API Security:** `Laravel Sanctum`
- **Coding Standard:** `Laravel Pint`

### Essential Packages
- **Permissions:** `spatie/laravel-permission`
- **Images:** `intervention/image` (Logo/Product image processing)
- **Reports:** `barryvdh/laravel-dompdf` & `maatwebsite/excel`
- **UI:** `lucide-react`, `recharts`, `shadcn/ui`

## 4. Database Strategy
- **Inventory Logs:** পণ্যের প্রতিটি মুভমেন্ট ট্র্যাক করে।
- **Transaction Logs:** টাকার প্রতিটি লেনদেন ট্র্যাক করে।
- **UUID Identity:** প্রতিটি মেইন টেবিলে Primary ID-র পাশাপাশি একটি ইউনিক `uuid` থাকবে।

## 5. Performance Strategy
- **Queues:** Redis ব্যবহার করে ইনভেন্টরি এবং ফিন্যান্স আপডেট ব্যাকগ্রাউন্ডে প্রসেস করা হবে।
- **Caching:** রিপোর্টিং ডাটার জন্য Redis ব্যবহার করা হবে।

## 6. Roadmap
- **Phase 1:** Retail & Pharmacy MVP (Current Focus).
- **Phase 2:** Restaurant (with Recipe Management).
- **Phase 3:** Assembly Line / Production (e.g., Easy-bike factory).
- **Phase 4:** Offline-First Sync Implementation (IndexedDB + Service Workers).

## 7. Modular Monolith Folder Structure
প্রজেক্টটি Single Codebase হবে, কিন্তু business capability অনুযায়ী module-এ ভাগ করা থাকবে। প্রতিটি module self-contained হবে, যাতে future-এ maintain, test এবং extend করা সহজ হয়।

```text
app/
├── Modules/
│   ├── Shared/
│   │   ├── Support/
│   │   ├── Traits/
│   │   ├── Enums/
│   │   └── Services/
│   │
│   ├── Inventory/
│   │   ├── Application/
│   │   │   ├── Actions/
│   │   │   ├── DTOs/
│   │   │   └── Services/
│   │   ├── Domain/
│   │   │   ├── Models/
│   │   │   ├── Enums/
│   │   │   ├── Rules/
│   │   │   └── Events/
│   │   ├── Infrastructure/
│   │   │   ├── Repositories/
│   │   │   └── Queries/
│   │   ├── Http/
│   │   │   ├── Controllers/
│   │   │   ├── Requests/
│   │   │   └── Resources/
│   │   ├── Database/
│   │   │   ├── Migrations/
│   │   │   ├── Seeders/
│   │   │   └── Factories/
│   │   ├── Routes/
│   │   │   ├── web.php
│   │   │   └── api.php
│   │   ├── Policies/
│   │   ├── Providers/
│   │   └── Tests/
│   │       ├── Feature/
│   │       └── Unit/
│   │
│   ├── Pos/
│   ├── Finance/
│   ├── CRM/
│   ├── HRM/
│   └── Reporting/
│
├── Models/
├── Http/
├── Providers/
└── Traits/

resources/
├── js/
│   ├── Pages/
│   ├── Components/
│   ├── Layouts/
│   └── Modules/
│       ├── Inventory/
│       ├── Pos/
│       ├── Finance/
│       └── Shared/
└── views/
```

## 8. Module Layer Responsibilities
প্রতিটি module-এর ভেতরে layer-এর responsibility পরিষ্কারভাবে আলাদা থাকবে।

- **Application:** Use case orchestration, actions, DTOs, workflow services।
- **Domain:** Business rules, entities/models, enums, events, validation rules।
- **Infrastructure:** Repositories, query handlers, external integration, persistence details।
- **Http:** Controllers, Form Requests, API Resources, route-facing classes।
- **Database:** Module-specific migrations, seeders, factories।
- **Providers:** Module bootstrapping, bindings, listeners registration।
- **Policies:** Authorization rules related to the module।
- **Tests:** Module-based unit and feature tests।

## 9. Shared vs Module-Specific Code
- `app/Modules/Shared` এ থাকবে cross-module reusable code।
- `app/Traits` এ global reusable trait রাখা যাবে, যেমন `HasUuid`।
- Module-specific trait, enum, helper বা service সেই module-এর ভিতরেই রাখতে হবে।
- Shared code কখনো specific business rule carry করবে না।
- Inventory-এর logic POS বা Finance-এর folder-এ লেখা যাবে না।

## 10. Routing Strategy
- Main application shell routes থাকবে global `routes/` folder-এ।
- Business module routes থাকবে `app/Modules/<ModuleName>/Routes/` folder-এ।
- Web route এবং API route আলাদা ফাইলে রাখা হবে।
- Module route naming হবে prefix-based।
- Example: `inventory.products.index`, `pos.sales.store`, `finance.expenses.index`

## 11. Frontend Module Strategy
- Shared UI components থাকবে `resources/js/Components`
- Global layouts থাকবে `resources/js/Layouts`
- Module-specific pages/components থাকবে `resources/js/Modules/<ModuleName>`
- Breeze/Auth/Profile এর মতো application-level pages global `resources/js/Pages` এ থাকতে পারে
- যদি কোনো module-এর UI বড় হয়, তাহলে তার page, partials, form, widgets module folder-এর ভেতরে রাখতে হবে

## 12. Naming Conventions
- Module name হবে singular business capability না, readable domain name: `Inventory`, `Pos`, `Finance`
- Controller names: `ProductController`, `SaleController`, `ExpenseController`
- Action names: `CreateProductAction`, `RecordSaleAction`, `PostExpenseAction`
- DTO names: `CreateProductData`, `SaleData`
- Event names: `StockAdjusted`, `SaleCompleted`, `ExpenseRecorded`
- Trait names: `HasUuid`, `HasTenant`, `LogsActivity`

## 13. Module Boundary Rules
- এক module আরেক module-এর internal class direct use করবে না, যদি shared contract বা event-based communication দিয়ে কাজ করা যায়।
- Cross-module communication prefer করা হবে Events, Actions, Interfaces, বা Shared Services দিয়ে।
- Finance module সরাসরি Inventory stock mutate করবে না।
- POS sale complete হলে event dispatch হবে, Inventory এবং Finance সেই event consume করবে।
- Reporting module read-heavy হবে, write logic নয়।

## 14. Initial Core Modules
- **Inventory:** Product, stock movement, batch, purchase stock intake
- **POS:** Cart, sale, invoice, return, discount
- **Finance:** Cashbook, transaction log, expense, payment tracking
- **Reporting:** Daily summary, profit/loss, stock report, sales analytics
- **Shared:** UUID trait, tenant context, common enums, shared support services

## 15. Implementation Guideline
- প্রতিটি নতুন business feature প্রথমে module identify করে তারপর folder create করতে হবে।
- Eloquent model module-এর `Domain/Models` এ রাখাই preferred।
- Complex business logic controller-এ লেখা যাবে না, Action/Service layer-এ নিতে হবে।
- Reusable UUID behavior trait আকারে রাখা হবে এবং যেসব model-এ `uuid` column থাকবে সেগুলোতে use করা হবে।
- শুরু থেকেই module-aware test structure বজায় রাখতে হবে।
