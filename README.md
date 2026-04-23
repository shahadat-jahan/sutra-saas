# Sutra - Modular Multi-tenant SaaS ERP/POS

Sutra is a modern, scalable, and modular SaaS platform designed for Bangladeshi retail and pharmacy businesses. Built with a **Modular Monolith** architecture, it supports multi-tenancy via subdomains and provides a seamless POS experience.

## 🛠 Tech Stack

- **Backend:** PHP 8.5 (Target) / Laravel 13
- **Frontend:** React via Inertia.js
- **Database:** PostgreSQL (with JSONB support)
- **UI:** Tailwind CSS + Shadcn UI
- **Architecture:** Modular Multi-tenant (Subdomain-based)

## 🚀 Core Features

- **Event-Driven Inventory:** Real-time stock tracking via background jobs.
- **Dynamic Module Control:** Enable/disable features (POS, Pharmacy, E-commerce) per shop.
- **Financial Tracking:** Full transaction logging for credit/debit management.
- **Subdomain Routing:** Each tenant gets a unique URL (e.g., `shop-name.sutra.com`).
- **Offline-Ready Foundations:** Built using UUIDs for seamless data synchronization.

## 📁 Architecture Overview

The project follows a modular structure where each domain (Inventory, Sales, Finance) is isolated to ensure long-term maintainability.

## 📚 Technical Documentation

Detailed architectural diagrams and workflows can be found here:

- [System Architecture Overview](./docs/architecture.md)
- [Multi-tenancy & Request Flow](./docs/tenancy_flow.md)
- [Event-driven POS Engine](./docs/event_engine.md)
- [Database Entity Relationships](./docs/database_erd.md)
- [Super Admin Workflow](./docs/admin_control.md)

---
*Developed by [Shahadat Jahan](https://linkedin.com/in/shahadat-jahan)*
