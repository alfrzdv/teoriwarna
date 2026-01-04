# TeoriWarna E-Commerce - Entity Relationship Diagram

## Database Entity Relationship Diagram (ERD)

```mermaid
erDiagram
    users ||--o{ user_addresses : "has many"
    users ||--o{ carts : "has many"
    users ||--o{ orders : "places"
    users ||--o{ product_reviews : "writes"
    users ||--o{ refunds : "requests"
    users ||--o{ refunds : "approves (admin)"

    categories ||--o{ products : "contains"

    products ||--o{ product_images : "has"
    products ||--o{ cart_items : "in"
    products ||--o{ order_items : "ordered as"
    products ||--o{ product_reviews : "reviewed in"

    carts ||--o{ cart_items : "contains"

    orders ||--o{ order_items : "contains"
    orders ||--o| payments : "has"
    orders ||--o| refunds : "may have"
    orders }o--o| user_addresses : "ships to"

    order_items ||--o| product_reviews : "can be reviewed"

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
        timestamps created_at_updated_at
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
        timestamps created_at_updated_at
    }

    categories {
        bigint id PK
        string name
        text description
        boolean is_active
        string background_color
        string text_color
        string style_type
        timestamps created_at_updated_at
    }

    products {
        bigint id PK
        bigint category_id FK
        string name
        decimal price
        text description
        integer stock
        enum status
        timestamps created_at_updated_at
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
        bigint order_item_id FK "nullable"
        integer rating
        text comment
        timestamps created_at_updated_at
    }

    carts {
        bigint id PK
        bigint user_id FK
        timestamps created_at_updated_at
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
        bigint address_id FK "nullable"
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
        timestamps created_at_updated_at
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
        timestamps created_at_updated_at
    }

    refunds {
        bigint id PK
        bigint order_id FK
        bigint user_id FK
        bigint approved_by FK "nullable"
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
        timestamps created_at_updated_at
    }
```

---

## Database Statistics

### Total Tables: 13
- users
- user_addresses
- categories
- products
- product_images
- product_reviews
- carts
- cart_items
- orders
- order_items
- payments
- refunds
- store_settings

### Total Relationships: 18
- **One-to-Many:** 16 relationships
- **Optional One-to-One:** 2 relationships
- **Self-referencing:** 1 relationship (refunds.approved_by → users)

### Foreign Keys: 17
- All with proper CASCADE or SET NULL constraints
- All properly indexed for performance

### Indexes Summary:
- **Performance Indexes:** 11 (products, cart_items, order_items, product_images, orders, reviews)
- **Foreign Key Indexes:** 7 (carts, user_addresses, orders, payments, refunds)
- **Composite Indexes:** 2 (products.status+category_id, product_images.product_id+is_primary)
- **Total:** 20 indexes

---

## Table Details

### Core User Tables
- **users** - User accounts with authentication
- **user_addresses** - Shipping addresses for users
- **carts** - Shopping cart per user
- **cart_items** - Items in shopping cart

### Product Tables
- **categories** - Product categories
- **products** - Product catalog
- **product_images** - Product images (multiple per product)
- **product_reviews** - Customer reviews and ratings

### Order Tables
- **orders** - Customer orders
- **order_items** - Line items in orders
- **payments** - Payment records with Midtrans integration
- **refunds** - Refund requests and processing

### Configuration
- **store_settings** - Global store configuration

---

## Relationship Details

### Users Relationships
```
users (1) ─────── (many) user_addresses [CASCADE DELETE]
users (1) ─────── (many) carts [CASCADE DELETE]
users (1) ─────── (many) orders [CASCADE DELETE]
users (1) ─────── (many) product_reviews [CASCADE DELETE]
users (1) ─────── (many) refunds [CASCADE DELETE]
users (1) ─────── (many) refunds.approved_by [SET NULL - optional]
```

### Product Relationships
```
categories (1) ─────── (many) products [CASCADE DELETE]
products (1) ─────── (many) product_images [CASCADE DELETE]
products (1) ─────── (many) product_reviews [CASCADE DELETE]
products (1) ─────── (many) cart_items [CASCADE DELETE]
products (1) ─────── (many) order_items [CASCADE DELETE]
```

### Cart Relationships
```
carts (1) ─────── (many) cart_items [CASCADE DELETE]
```

### Order Relationships
```
orders (1) ─────── (many) order_items [CASCADE DELETE]
orders (1) ─────── (1) payments [CASCADE DELETE]
orders (1) ─────── (many) refunds [CASCADE DELETE]
orders (many) ─────── (1) user_addresses [SET NULL - optional]
```

### Review Relationships
```
order_items (1) ─────── (many) product_reviews [SET NULL - optional]
```

---

## Enum Values

### users.role
- `user` (default)
- `admin`
- `super_admin`

### products.status
- `active` (default)
- `inactive`
- `archived`

### orders.status
- `pending` (awaiting payment)
- `paid` (payment received)
- `processing` (being prepared)
- `shipped` (on delivery)
- `completed` (delivered)
- `cancelled` (cancelled by customer/admin)
- `refunded` (refund processed)

### payments.method
- `transfer` (bank transfer)
- `ewallet` (e-wallet: GoPay, OVO, DANA)
- `cod` (cash on delivery)

### payments.status
- `pending` (default)
- `success` (payment confirmed)
- `failed` (payment failed)

### refunds.refund_method
- `bank_transfer`
- `e_wallet`
- `store_credit`

### refunds.status
- `pending` (default)
- `approved`
- `rejected`
- `processing`
- `completed`

---

## Indexing Strategy

### Primary Indexes (Performance)
```
✅ products.category_id
✅ products.status
✅ products.(status, category_id) - composite
✅ cart_items.cart_id
✅ cart_items.product_id
✅ order_items.order_id
✅ order_items.product_id
✅ product_images.product_id
✅ product_images.(product_id, is_primary) - composite
✅ orders.user_id
✅ orders.status
✅ product_reviews.product_id
✅ product_reviews.user_id
```

### Foreign Key Indexes (Added)
```
✅ carts.user_id
✅ user_addresses.user_id
✅ orders.address_id
✅ payments.order_id
✅ refunds.order_id
✅ refunds.user_id
✅ refunds.approved_by
```

---

**Last Updated:** January 4, 2026
**Database Version:** After migration 2026_01_04_050000
**Total Indexes:** 20
**Total Foreign Keys:** 17
