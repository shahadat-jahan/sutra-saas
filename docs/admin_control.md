```mermaid
graph TD
    SA["Super Admin"] -->|Toggle Features| S["Shop Configuration"]
    
    subgraph "Subscription Features"
        S --> M1["Bakir Khata (WhatsApp/SMS)"]
        S --> M2["DGDA Pharma Database"]
        S --> M3["Multi-user Support"]
    end
    
    M1 & M2 & M3 --> P["Pricing Plan: Basic / Pro / Enterprise"]
