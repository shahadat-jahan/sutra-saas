sequenceDiagram
    participant User
    participant Middleware as TenantMiddleware
    participant Auth as Spatie Permissions
    participant DB as PostgreSQL

    User->>Middleware: Request shop1.sutra.com/pos
    Middleware->>DB: Fetch Shop where slug = 'shop1'
    Middleware->>Middleware: Set Global Tenant Context (shop_id)
    Middleware->>Auth: Check 'make-sale' permission
    Auth-->>User: Grant Access
    User->>DB: Query Products
    DB-->>User: Return Products (Automatically Filtered by shop_id)
