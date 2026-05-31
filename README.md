# Sutra - Advanced B2B Distribution & Dealer Management SaaS

Sutra is an enterprise-grade, scalable, and modular SaaS platform engineered specifically for large-scale wholesale distributors, manufacturers, and multi-tier dealer networks. Built on a robust **Modular Monolith** architecture, Sutra provides complete visibility and control over complex distribution lifecycles—from high-volume order processing to automated financial settlements.

## 🚀 Key Features

- **Multi-tenant Distribution:** True subdomain-based isolation ensures data security while allowing distributors to manage isolated regions, warehouses, or subsidiary networks efficiently.
- **Slab-Based Discount Engine:** Advanced algorithmic pricing tiers driven by order volume. Configurable across product categories, global territories, and specific dealer classifications (e.g., Bronze, Silver, Gold).
- **Automated Dealer Commissions:** A comprehensive backend settlement engine automatically tracks, calculates, and generates payable commission statements based on completed order ledgers and configurable period rules.
- **Ledger-First Financial Tracking:** A highly auditable double-entry accounting foundation. Every transaction—from credit extension to commission payouts—is logged immutably, ensuring perfect financial reconciliation.
- **Event-Driven Fulfillment Architecture:** Stock allocation, discount application, and ledger adjustments are processed asynchronously via our high-throughput Redis-backed Event Engine.
- **Offline-Ready Foundations:** Built using universally unique identifiers (UUIDs) to facilitate seamless data synchronization for field agents operating in low-connectivity areas.

## 🛠 Tech Stack

- **Backend:** PHP 8.5 (Target) / Laravel 13
- **Frontend:** React via Inertia.js
- **Database:** PostgreSQL (with JSONB support for dynamic attributes)
- **UI:** Tailwind CSS + Shadcn UI
- **Queue & Cache:** Redis for async background jobs and ledger rate-limiting
- **Architecture:** Modular Monolith with Event-Driven Communication

## 🐳 Docker Setup (Laravel Sail)

To run the application locally using Docker and Laravel Sail:

```bash
# Install PHP dependencies
composer install

# Start the Docker containers
./vendor/bin/sail up -d

# Run database migrations
./vendor/bin/sail artisan migrate

# Install and build frontend assets
./vendor/bin/sail npm install
./vendor/bin/sail npm run dev
```

## 📁 Architecture Overview

The project follows a highly decoupled modular structure where each business domain (Dealer, Sales, Finance, Inventory, Discount) is isolated to ensure long-term maintainability, strictly enforcing the Service-Repository pattern.

## 📚 Technical Documentation

Detailed architectural diagrams and workflows can be found here:

- [System Architecture Overview](./docs/architecture.md)
- [Multi-tenancy & Request Flow](./docs/tenancy_flow.md)
- [Event-driven Distribution Engine](./docs/event_engine.md)
- [Database Entity Relationships](./docs/database_erd.md)
- [Super Admin Workflow](./docs/admin_control.md)

---
*Developed by [Shahadat Jahan](https://linkedin.com/in/shahadat-jahan)*
