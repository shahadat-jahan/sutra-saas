graph TD
    SA[Super Admin] -->|Toggle Modules| S[Shop Settings]
    SA -->|Manage Subscriptions| B[Billing Engine]
    S -->|Enable/Disable| M{Modules: POS, Pharmacy, E-com}
    B -->|Update Plan| P[Basic / Pro / Enterprise]