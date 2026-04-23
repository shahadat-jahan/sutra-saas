graph LR
    A[POS Checkout] --> B(Order Created)
    B --> C{Dispatch SaleCompleted Event}

    subgraph "Background Processing (Laravel Queue)"
        C --> D[Update Inventory Stock]
        C --> E[Create Financial Transaction Log]
        C --> F[Sync Daily Analytics]
    end
    
    D --> G[(Database)]
    E --> G
    F --> G
