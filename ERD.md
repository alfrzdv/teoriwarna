# TeoriWarna E-Commerce - Database Schema

## Entity Relationship Diagram

```mermaid
%%{init: {'theme':'base', 'themeVariables': { 'primaryColor':'#ffffff','primaryTextColor':'#1a1a1a','primaryBorderColor':'#e0e0e0','lineColor':'#757575','secondaryColor':'#fafafa','tertiaryColor':'#f5f5f5','background':'#ffffff','fontSize':'14px','fontFamily':'Georgia, serif'}}}%%

erDiagram
    users ||--o{ user_addresses : has
    users ||--o{ carts : has
    users ||--o{ orders : places
    users ||--o{ product_reviews : writes
    users ||--o{ refunds : requests
    categories ||--o{ products : contains
    products ||--o{ product_images : has
    products ||--o{ cart_items : in
    products ||--o{ order_items : ordered
    products ||--o{ product_reviews : reviewed
    carts ||--o{ cart_items : contains
    orders ||--o{ order_items : contains
    orders ||--o| payments : has
    orders ||--o| refunds : may_have
    orders }o--o| user_addresses : ships_to
    order_items ||--o| product_reviews : reviewed
    
    users {
        bigint id PK
        string name
        string email UK
        string password
        string phone
        enum role
        boolean is_active
        boolean is_banned
        timestamp last_login
        timestamps created_updated
    }
    user_addresses {
        bigint id PK
        bigint user_id FK
        string recipient_name
        string phone
        text address
        string city
        string province
        string postal_code
        boolean is_default
        timestamps created_updated
    }
    categories {
        bigint id PK
        string name
        text description
        boolean is_active
        string background_color
        string text_color
        string style_type
        timestamps created_updated
    }
    products {
        bigint id PK
        bigint category_id FK
        string name
        decimal price
        text description
        integer stock
        enum status
        timestamps created_updated
    }
    product_images {
        bigint id PK
        bigint product_id FK
        string image_path
        boolean is_primary
        timestamp created_at
    }
    product_reviews {
        bigint id PK
        bigint product_id FK
        bigint user_id FK
        bigint order_item_id FK
        integer rating
        text comment
        timestamps created_updated
    }
    carts {
        bigint id PK
        bigint user_id FK
        timestamps created_updated
    }
    cart_items {
        bigint id PK
        bigint cart_id FK
        bigint product_id FK
        integer quantity
        decimal price
        decimal subtotal
    }
    orders {
        bigint id PK
        bigint user_id FK
        bigint address_id FK
        string order_number UK
        decimal total_amount
        enum status
        string shipping_name
        string shipping_phone
        text shipping_address
        string shipping_city
        string shipping_postal_code
        decimal shipping_cost
        string shipping_method
        string tracking_number
        string shipping_courier
        text notes
        timestamps created_updated
    }
    order_items {
        bigint id PK
        bigint order_id FK
        bigint product_id FK
        integer quantity
        decimal price
        decimal subtotal
    }
    payments {
        bigint id PK
        bigint order_id FK
        enum method
        enum status
        timestamp payment_date
        string proof
        text rejection_reason
        string snap_token
        string transaction_id
        string transaction_status
        timestamp paid_at
        timestamps created_updated
    }
    refunds {
        bigint id PK
        bigint order_id FK
        bigint user_id FK
        bigint approved_by FK
        string refund_number UK
        decimal refund_amount
        enum refund_method
        enum status
        text reason
        text admin_notes
        json bank_details
        timestamp approved_at
        timestamp rejected_at
        timestamp completed_at
        timestamps created_updated
    }
```

---

## Database Overview

**Total Tables:** 13  
**Total Relationships:** 16  
**Total Foreign Keys:** 17  
**Total Indexes:** 20

### Table Categories

**User Management** - users, user_addresses, carts, cart_items  
**Product Catalog** - categories, products, product_images, product_reviews  
**Order Processing** - orders, order_items, payments, refunds

---

## Enum Definitions

### users.role
`user` (default), `admin`, `super_admin`

### products.status
`active` (default), `inactive`, `archived`

### orders.status
`pending`, `paid`, `processing`, `shipped`, `completed`, `cancelled`, `refunded`

### payments.method
`transfer`, `ewallet`, `cod`

### payments.status
`pending` (default), `success`, `failed`

### refunds.refund_method
`bank_transfer`, `e_wallet`, `store_credit`

### refunds.status
`pending`, `approved`, `rejected`, `processing`, `completed`

---

## Key Relationships

### User Relations
Users have multiple addresses, carts, orders, and reviews. Users can request refunds, and admin users can approve refunds.

### Product Relations
Products belong to categories and can have multiple images, reviews, and be in carts or orders. All product-related data cascades on product deletion.

### Order Relations
Orders contain multiple items, have one payment record, and may have refund requests. Orders reference user addresses for shipping.

### Review Relations
Reviews are linked to specific order items (optional), ensuring only verified purchases can be reviewed.

---

## Indexing Strategy

**Performance Indexes:** Product status, category lookups, cart operations, order queries  
**Foreign Key Indexes:** All relationships properly indexed  
**Composite Indexes:** Product filtering (status + category), primary image selection

---

**System:** TeoriWarna E-Commerce  
**Database Version:** 1.0.0  
**Last Updated:** January 4, 2026