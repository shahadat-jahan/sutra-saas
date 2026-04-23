erDiagram
    SHOPS ||--o{ USERS : owns
    SHOPS ||--o{ PRODUCTS : manages
    SHOPS ||--o{ SALES : records
    PRODUCTS ||--o{ SALE_ITEMS : included_in
    SALES ||--|{ SALE_ITEMS : has
    SALES ||--o{ TRANSACTION_LOGS : generates
