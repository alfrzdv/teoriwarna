# 🎨 TeoriWarna.shop - Filament Admin Panel Setup Guide

## 📋 Overview

**teoriwarna.shop** adalah e-commerce platform dengan Filament Admin Panel yang full-featured dan colorful brutalist design.

**Author:** Al Farizd Syawaludin (607022400043)

---

## 🚀 Quick Start

### Prerequisites
- PHP >= 8.1
- Composer
- MySQL/PostgreSQL
- Node.js & NPM

### Installation Steps

```bash
# 1. Install dependencies
composer install
npm install

# 2. Setup environment
cp .env.example .env
php artisan key:generate

# 3. Configure database (.env)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=teoriwarna
DB_USERNAME=root
DB_PASSWORD=

# 4. Run migrations
php artisan migrate

# 5. Create admin user
php artisan make:filament-user

# 6. Build assets
npm run build

# 7. Start server
php artisan serve
```

**Access Admin Panel:** http://localhost:8000/admin

---

## 📦 Filament Resources Implemented

### 1. **OrderResource** 🛒
**Location:** `app/Filament/Resources/OrderResource.php`

**Features:**
- ✅ Full CRUD operations
- ✅ Status tracking (Pending → Paid → Processing → Shipped → Completed)
- ✅ Update status with modal form
- ✅ Tracking number management
- ✅ Tabs for each status
- ✅ Date range filtering
- ✅ Badge notification for pending orders
- ✅ Detailed infolist with order items, shipping, payment info

**Navigation:** Admin Panel → Transaksi → Pesanan

---

### 2. **UserResource** 👥
**Location:** `app/Filament/Resources/UserResource.php`

**Features:**
- ✅ User CRUD with profile picture
- ✅ Role management (User/Admin)
- ✅ Ban/Unban toggle action
- ✅ Tabs (All, Users, Admins, Banned)
- ✅ User statistics (orders, total spent, addresses)
- ✅ Password management
- ✅ Search by name/email

**Navigation:** Admin Panel → User Management → Users

---

### 3. **ProductResource** 🛍️
**Location:** `app/Filament/Resources/ProductResource.php`

**Features:**
- ✅ Product CRUD
- ✅ **Multiple image upload (max 5 images)**
- ✅ **Stock Management System:**
  - Add/Reduce stock with action
  - Initial stock on create
  - Real-time stock display
  - Low stock alerts (≤10 units)
  - Color-coded badges (green/yellow/red)
- ✅ Category management with inline create
- ✅ Bulk activate/deactivate
- ✅ Low stock filter
- ✅ Navigation badge for low stock products

**Stock Management:**
Stock is managed directly through the product form and can be adjusted using the Add/Reduce Stock actions in the Filament admin panel.

**Navigation:** Admin Panel → Toko → Produk

---

### 4. **PaymentResource** 💳
**Location:** `app/Filament/Resources/PaymentResource.php`

**Features:**
- ✅ Payment verification
- ✅ Approve/Reject actions
- ✅ View proof of payment (image)
- ✅ Rejection reason tracking
- ✅ Tabs (All, Pending, Success, Failed)
- ✅ Filter by payment method & date
- ✅ Badge notification for pending payments

**Payment Methods Supported:**
- Bank Transfer
- E-Wallet
- Cash on Delivery (COD)

**Navigation:** Admin Panel → Transaksi → Pembayaran

---

### 5. **RefundResource** ↩️
**Location:** `app/Filament/Resources/RefundResource.php`

**Features:**
- ✅ Refund request management
- ✅ Status workflow (Pending → Approved → Processing → Completed/Rejected)
- ✅ Approve/Reject actions
- ✅ Rejection reason tracking
- ✅ Amount tracking
- ✅ Linked to orders
- ✅ Tabs for each status
- ✅ Badge notification for pending refunds

**Navigation:** Admin Panel → Transaksi → Refunds

---

### 6. **ReviewResource** ⭐
**Location:** `app/Filament/Resources/ReviewResource.php`

**Features:**
- ✅ Product review moderation
- ✅ Rating display (1-5 stars)
- ✅ Approve/Reject actions
- ✅ View review images
- ✅ Verified purchase indicator
- ✅ Bulk approve/reject
- ✅ Filter by rating & status
- ✅ Tabs (All, Pending, Approved, Rejected, 5★)

**Navigation:** Admin Panel → Support → Reviews

---

### 7. **CategoryResource** 📂
**Location:** `app/Filament/Resources/CategoryResource.php`

**Features:**
- ✅ Category CRUD
- ✅ Hierarchical organization
- ✅ Product count per category

**Navigation:** Admin Panel → Toko → Kategori

---

## 🎨 Theme Customization

**Theme File:** `resources/css/filament/admin/theme.css`

### Design Features:
```css
/* Colorful Gradient Sidebar */
.fi-sidebar {
    background: linear-gradient(to bottom, #2563eb, #9333ea, #ec4899);
}

/* Yellow Gradient Topbar */
.fi-topbar {
    background: linear-gradient(to right, #fbbf24, #f59e0b);
}

/* Brutalist Cards with Shadows */
.fi-card {
    border: 4px solid black;
    box-shadow: 8px 8px 0px 0px rgba(0, 0, 0, 1);
}

/* Alternating Table Rows */
.fi-ta-row:nth-child(odd) { background: #dbeafe; }
.fi-ta-row:nth-child(even) { background: #fce7f3; }
```

### Rebuild Theme:
```bash
npm run build
```

---

## 🏠 Landing Page

**File:** `resources/views/home.blade.php`

### Features:
- ✅ Animated gradient blobs background
- ✅ Hero section with massive gradient text
- ✅ User Access Flow (Guest/Login/Register)
- ✅ Product Management showcase
- ✅ Payment Processing info
- ✅ Fully responsive design

**Access:** http://localhost:8000/

---

## 📊 Navigation Structure

```
Admin Panel (/admin)
├── Dashboard
├── Toko
│   ├── Produk (badge: low stock count)
│   └── Kategori
├── Transaksi
│   ├── Pesanan (badge: pending count)
│   ├── Pembayaran (badge: pending count)
│   └── Refunds (badge: pending count)
├── Support
│   ├── Refunds (badge: pending count)
│   └── Reviews (badge: pending count)
└── User Management
    └── Users (badge: total users)
```

---

## 🔧 Common Tasks

### Create Admin User
```bash
php artisan make:filament-user
```

### Clear Cache
```bash
php artisan optimize:clear
```

### Rebuild Assets
```bash
npm run build
```

### Database Fresh Install
```bash
php artisan migrate:fresh --seed
```

---

## 📝 Model Relationships

### Order Model
```php
$order->user           // BelongsTo User
$order->order_items    // HasMany OrderItem
$order->payment        // HasOne Payment
$order->refund         // HasOne Refund
```

### Product Model
```php
$product->category          // BelongsTo Category
$product->product_images    // HasMany ProductImage
$product->stock             // Direct stock field (integer)
```

### User Model
```php
$user->orders           // HasMany Order
$user->user_addresses   // HasMany UserAddress
$user->complaints       // HasMany Complaint
```

---

## 🎯 Key Features

### Stock Management
Stock is managed directly on the product model with the `stock` field (integer). Admin can add or reduce stock using actions in the Filament admin panel.

### Order Status Flow
```
Pending → Paid → Processing → Shipped → Completed
                                     ↓
                                 Cancelled
```

### Payment Status Flow
```
Pending → Success / Failed
```

### Refund Status Flow
```
Pending → Approved → Processing → Completed
                              ↓
                          Rejected
```

---

## 🚨 Troubleshooting

### Assets not loading
```bash
npm run build
php artisan optimize:clear
```

### Database issues
```bash
php artisan migrate:fresh
```

### Permission errors
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

---

## 📱 Responsive Design

All Filament resources are **fully responsive** and work on:
- 📱 Mobile (320px+)
- 📱 Tablet (768px+)
- 💻 Desktop (1024px+)
- 🖥️ Large Desktop (1920px+)

---

## 🎨 Color Scheme

### Primary Colors:
- **Purple:** `#9333ea` (Buttons, accents)
- **Pink:** `#ec4899` (Highlights)
- **Blue:** `#2563eb` (Links, info)
- **Yellow:** `#fbbf24` (Warnings, topbar)

### Status Colors:
- **Success:** Green `#10b981`
- **Warning:** Yellow `#f59e0b`
- **Danger:** Red `#ef4444`
- **Info:** Blue `#3b82f6`

---

## 📚 Resources

- **Filament Docs:** https://filamentphp.com/docs
- **Laravel Docs:** https://laravel.com/docs
- **Tailwind CSS:** https://tailwindcss.com

---

## 🏆 Credits

**Developer:** Al Farizd Syawaludin
**Student ID:** 607022400043
**Project:** teoriwarna.shop
**Tech Stack:** Laravel 11 + Filament 3 + Tailwind CSS

---

**🎉 Happy Managing! 🚀**
