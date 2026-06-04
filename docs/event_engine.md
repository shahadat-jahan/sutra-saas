```mermaid
graph LR
    A["POS Sale (Credit/Baki)"] --> B["Order Created"]
    B --> C["Dispatch SaleCompleted Event"]
    
    subgraph "Background Processing (Laravel Queue)"
        C --> D["Update Stock"]
        C --> E["Finance Ledger Entry"]
        C --> F["WhatsApp/SMS Reminder (Bakir Khata)"]
    end
    
    F --> G["External Gateway (WhatsApp/SMS API)"]
    D & E --> H[("Database")]
