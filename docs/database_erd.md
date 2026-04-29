```mermaid
erDiagram
    SHOPS ||--o{ CUSTOMERS : "manages credit"
    CUSTOMERS {
        string name
        decimal credit_limit
        decimal due_amount
    }
    SHOPS ||--o{ PRODUCTS : "manages"
    PRODUCTS {
        string name
        string generic_name
        string dgda_code
    }
    SHOPS ||--o{ SALES : "records"
    SALES ||--o{ REMINDERS : "triggers"
    REMINDERS {
        string status
        datetime scheduled_at
    }
