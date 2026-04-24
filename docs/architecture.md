```mermaid
graph TD
    A[Public Web / Landing Page] --> B{Subdomain Router}
    B -->|shop1.sutra.com| C[Tenant Context: Shop 1]
    B -->|shop2.sutra.com| D[Tenant Context: Shop 2]
    
    subgraph "Modular Monolith (Laravel 13)"
        C --> E[POS Module]
        C --> F[Inventory Module]
        C --> G[Accounting Module]
    end

    E & F & G --> H[(Shared PostgreSQL Database)]
    H --> I[Isolated by shop_id Global Scope]
