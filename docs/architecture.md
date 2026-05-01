```mermaid
graph TD
    A["Public Web / Landing Page"] --> B{Subdomain Router}
    B -->|shop1.sutra.com| C["Tenant Context: Shop 1"]
    
    subgraph "Modular Monolith (Laravel 13)"
        C --> E["POS & Bakir Khata"]
        C --> F["Inventory & DGDA Sync"]
        C --> G["Accounting Ledger"]
    end

    subgraph "External Integration"
        F <--> DX["DGDA Medicine Database"]
    end

    E & F & G --> H[("Shared PostgreSQL Database")]
    H --> I["Isolated by shop_id Global Scope"]
