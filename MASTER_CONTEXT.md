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
