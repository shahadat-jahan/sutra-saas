```mermaid
sequenceDiagram
    participant User
    participant Middleware as TenantMiddleware
    participant Auth as Feature/Module Guard
    participant DB as PostgreSQL
    
    User->>Middleware: Request shop1.sutra.com/pos
    Middleware->>DB: Fetch Shop & Enabled Modules
    Middleware->>Middleware: Set Global shop_id
    Middleware->>Auth: Check if 'Pharma' or 'Bakir Khata' is enabled
    Auth-->>User: Grant Access to Specific Features
    User->>DB: Query Data (Filtered by shop_id)
