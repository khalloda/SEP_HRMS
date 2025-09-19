```mermaid
graph TD
    subgraph External
        A[Public Internet] -->|HTTPS| LB[Load Balancer/SSL]
        C[ZKTeco Devices] -->|HMAC Auth| API[API Gateway]
    end

    subgraph Web Tier
        LB --> WEB[Web Application]
        API --> API_H[API Handlers]
    end

    subgraph App Tier
        WEB --> AUTH[Auth Service]
        WEB --> EMP[Employee Service]
        WEB --> DOC[Document Service]
        WEB --> PAY[Payroll Service]
        API_H --> ATT[Attendance Service]
    end

    subgraph Data Tier
        EMP --> DB[(MySQL Database)]
        DOC --> FS[File Storage]
        PAY --> DB
        ATT --> DB
    end

    subgraph Security Controls
        SC1[RBAC] -.-> WEB
        SC2[Field Encryption] -.-> DB
        SC3[Audit Logging] -.-> APP[All Services]
        SC4[Input Validation] -.-> API_H
        SC5[File Access Control] -.-> FS
    end

    subgraph Trust Zones
        style TZ1 fill:#f9f,stroke:#333
        TZ1[DMZ] -.-> LB
        TZ1 -.-> API
        
        style TZ2 fill:#ff9,stroke:#333
        TZ2[Trusted Zone] -.-> WEB
        TZ2 -.-> API_H
        
        style TZ3 fill:#9f9,stroke:#333
        TZ3[Secure Zone] -.-> DB
        TZ3 -.-> FS
    end