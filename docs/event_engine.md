# 🔄 Sutra Event Engine — B2B Distribution Flows

The Event Engine is the asynchronous backbone of Sutra's distribution system. All high-volume operations — stock mutations, ledger entries, commission calculations, and settlement — are processed via Laravel Queues backed by Redis.

---

## 1. Order Fulfillment Flow

```mermaid
graph LR
    A["Dealer Order Placed"] --> B["OrderCreated Event"]
    B --> C["Dispatch to Queue"]
    
    subgraph "Background Processing (Laravel Queue)"
        C --> D["Validate Dealer Credit"]
        C --> E["Apply Slab Discount"]
        C --> F["Reserve Stock (Inventory)"]
        C --> G["Create Ledger Entry (Finance)"]
        C --> H["Send Order Confirmation"]
    end
    
    D -->|"Insufficient Credit"| I["Hold for Approval"]
    F & G --> J[("Database")]
    H --> K["WhatsApp/SMS/Email Notification"]
```

---

## 2. Payment & Settlement Flow

```mermaid
graph LR
    A["Payment Received"] --> B["PaymentRecorded Event"]
    B --> C["Dispatch to Queue"]
    
    subgraph "Background Processing (Laravel Queue)"
        C --> D["Update Dealer Outstanding Balance"]
        C --> E["Settle Ledger Entry (FIFO Matching)"]
        C --> F["Check Commission Eligibility"]
        C --> G["Update Dealer Credit Score"]
    end
    
    D & E --> H[("Database")]
    F --> I["Queue for Commission Calculation"]
```

---

## 3. Periodical Commission Settlement

```mermaid
graph TD
    A["Scheduled Job: CommissionPeriodClose"] --> B["CommissionPeriodClosed Event"]
    B --> C["Fetch Settled Orders for Period"]
    C --> D["Apply Commission Rules per Dealer/Agent"]
    D --> E["Generate CommissionStatement Records"]
    E --> F["CommissionSettled Event"]
    
    subgraph "Settlement Processing"
        F --> G["Create Ledger Entries (Finance)"]
        F --> H["Generate Payment Instructions"]
        F --> I["Notify Dealers/Agents"]
    end
    
    G --> J[("Database")]
    H --> K["Bank/bKash API"]
    I --> L["WhatsApp/SMS/Email"]
```

---

## 4. Key Events Reference

| Event | Dispatched By | Listeners |
|-------|--------------|-----------|
| `OrderCreated` | Sales Module | Inventory (stock reserve), Finance (ledger), Notification |
| `OrderFulfilled` | Sales Module | Inventory (stock confirm), Finance (invoice finalize) |
| `PaymentRecorded` | Finance Module | Sales (balance update), Finance (settlement), Commission (eligibility) |
| `CommissionPeriodClosed` | Scheduled Job | Finance (commission calc), Notification (payout alerts) |
| `CommissionSettled` | Finance Module | Finance (ledger entries), Notification (dealer/agent alerts) |
| `DealerCreditExceeded` | Sales Module | Notification (approval request), Reporting (risk flag) |
| `SlabDiscountApplied` | Discount Module | Finance (price audit log), Reporting (discount analytics) |

---

## 5. Queue Configuration

*   **High Priority**: `OrderCreated`, `PaymentRecorded` — processed immediately.
*   **Default Priority**: `OrderFulfilled`, `DealerCreditExceeded` — processed within minutes.
*   **Low Priority (Batch)**: `CommissionPeriodClosed` — scheduled, processes large datasets in chunks.
*   **Retry Policy**: All events are idempotent with unique `idempotency_key` to prevent duplicate ledger entries on retry.
*   **Dead Letter Queue**: Failed events after 3 retries are moved to `failed_jobs` for manual inspection.
