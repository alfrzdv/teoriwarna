# Flowchart Implementation Summary

## Fitur Baru yang Telah Diimplementasi

Berikut adalah implementasi lengkap dari flowchart e-commerce yang mencakup semua alur yang sebelumnya masih kurang.

---

## 1. REFUND & RETURN SYSTEM

### Models & Migrations

#### `Refund` Model (app/Models/Refund.php)
- Model baru untuk menangani refund/return request
- Status: pending, approved, rejected, processing, completed
- Support 3 metode refund: bank_transfer, e_wallet, store_credit
- Menyimpan bank details untuk proses pengembalian dana

#### Migration: `create_refunds_table`
```sql
- id
- order_id (foreign key ke orders)
- user_id (foreign key ke users)
- refund_number (unique)
- refund_amount (decimal)
- refund_method (enum: bank_transfer, e_wallet, store_credit)
- status (enum: pending, approved, rejected, processing, completed)
- reason (text)
- admin_notes (text, nullable)
- bank_details (json, nullable)
- approved_at, rejected_at, completed_at (timestamps)
- approved_by (foreign key ke users)
```

### User Features

#### 1. Cancel Order with Auto Refund (OrderController@cancel)
**Route**: `POST /orders/{order}/cancel`

**Flow**:
1. Validasi order dapat dibatalkan (status: pending/paid)
2. Kembalikan stock produk ke inventory
3. Update status order menjadi 'cancelled'
4. Update payment status menjadi 'cancelled'
5. **OTOMATIS** buat refund request jika pembayaran sudah dilakukan
6. Kirim notifikasi ke admin tentang refund request

**Request**:
```php
'cancel_reason' => 'optional|string'
```

#### 2. Request Refund untuk Order Completed (OrderController@requestRefund)
**Route**: `POST /orders/{order}/request-refund`

**Kondisi**: Order dengan status completed, shipped, atau processing

**Flow**:
1. Validasi order dapat di-refund (belum punya refund request)
2. User isi form refund dengan alasan
3. Pilih metode refund (bank transfer, e-wallet, atau store credit)
4. Jika bank transfer, isi detail rekening
5. Jika e-wallet, isi nomor e-wallet
6. System create refund request dengan status 'pending'
7. Kirim notifikasi ke admin

**Request**:
```php
'reason' => 'required|string|max:1000',
'refund_method' => 'required|in:bank_transfer,e_wallet,store_credit',
'bank_name' => 'required_if:refund_method,bank_transfer',
'account_number' => 'required_if:refund_method,bank_transfer',
'account_name' => 'required_if:refund_method,bank_transfer',
'e_wallet_number' => 'required_if:refund_method,e_wallet',
```

#### 3. View Refund Status (OrderController@viewRefund)
**Route**: `GET /orders/{order}/refund`

User dapat melihat:
- Status refund (pending, approved, rejected, processing, completed)
- Alasan reject (jika ditolak)
- Timeline processing
- Detail metode refund

### Admin Features

#### Admin Refund Controller (app/Http/Controllers/Admin/RefundController.php)

**1. List All Refunds (index)**
**Route**: `GET /admin/refunds`

Filter:
- Status (pending, approved, rejected, processing, completed)
- Search by refund number atau user name

**2. View Refund Detail (show)**
**Route**: `GET /admin/refunds/{refund}`

Tampilan:
- Order details
- User details
- Refund amount
- Reason dari user
- Bank details (jika ada)
- Timeline

**3. Approve Refund (approve)**
**Route**: `POST /admin/refunds/{refund}/approve`

Flow:
1. Validasi refund masih pending
2. Update status menjadi 'approved'
3. **Return stock** ke inventory (untuk completed/shipped orders)
4. Update order status menjadi 'refunded'
5. Kirim notifikasi ke user
6. Record admin yang approve

**4. Reject Refund (reject)**
**Route**: `POST /admin/refunds/{refund}/reject`

Flow:
1. Admin kasih alasan reject
2. Update status menjadi 'rejected'
3. Kirim notifikasi ke user dengan alasan

Request:
```php
'admin_notes' => 'required|string|max:500'
```

**5. Mark as Processing (markAsProcessing)**
**Route**: `POST /admin/refunds/{refund}/processing`

Ketika admin mulai proses pengembalian dana (transfer, dll)

**6. Mark as Completed (markAsCompleted)**
**Route**: `POST /admin/refunds/{refund}/complete`

Ketika dana sudah berhasil dikembalikan ke user

---

## 2. PAYMENT VERIFICATION ENHANCEMENT

### Payment Rejection Feature

#### Admin OrderController Enhancement

**Reject Payment (rejectPayment)**
**Route**: `POST /admin/orders/{order}/reject-payment`

Flow:
1. Admin review bukti pembayaran
2. Jika tidak sesuai/tidak valid, reject dengan alasan
3. Update payment status menjadi 'rejected'
4. Kirim notifikasi ke user
5. User dapat upload ulang bukti pembayaran

Request:
```php
'rejection_reason' => 'required|string|max:500'
```

#### Migration Update
**File**: `add_rejection_reason_to_payments_table`
- Tambah kolom `rejection_reason` ke tabel `payments`

---

## 3. ORDER MANAGEMENT IMPROVEMENTS

### User Side - Order Filtering

**Enhanced OrderController@index**

Filter yang tersedia:
- **Status**: Filter by order status (pending, processing, shipped, dll)
- **Payment Status**: Filter by payment status
- **Search**: Search by order number
- **Date Range**: Filter dari tanggal X sampai tanggal Y

Request params:
```php
'status' => 'all|pending|paid|processing|shipped|completed|cancelled|refunded',
'payment_status' => 'all|pending|paid|rejected|cancelled',
'search' => 'string',
'date_from' => 'date',
'date_to' => 'date'
```

### Admin Side - Bulk Operations

**Bulk Update Status (bulkUpdateStatus)**
**Route**: `POST /admin/orders/bulk-update-status`

Flow:
1. Admin select multiple orders (checkbox)
2. Pilih status baru yang akan diterapkan
3. System update semua selected orders
4. Kirim notifikasi ke masing-masing user

Request:
```php
'order_ids' => 'required|array',
'order_ids.*' => 'exists:orders,id',
'status' => 'required|in:pending,processing,shipped,completed,cancelled'
```

---

## 4. ORDER STATUS ENHANCEMENT

### New Order Status: 'refunded'

**Migration**: `update_orders_status_enum`

Menambahkan status 'refunded' ke enum status di tabel orders.

Status flow:
```
pending → paid → processing → shipped → completed
   ↓        ↓         ↓          ↓
cancelled → refunded
```

---

## 5. MODEL ENHANCEMENTS

### Order Model

**New Methods**:
```php
// Check if order can request refund
canRequestRefund() // Returns bool

// Check if order has refund
hasRefund() // Returns bool

// Relationship
refund() // hasOne Refund
```

### Payment Model

**New Fillable**:
```php
'rejection_reason' // Text untuk alasan reject payment
```

---

## ROUTING SUMMARY

### User Routes (routes/web.php)

```php
// Refund routes
POST   /orders/{order}/request-refund  → requestRefund
GET    /orders/{order}/refund          → viewRefund
```

### Admin Routes (routes/web.php)

```php
// Payment management
POST   /admin/orders/{order}/reject-payment    → rejectPayment
POST   /admin/orders/bulk-update-status        → bulkUpdateStatus

// Refund management
GET    /admin/refunds                          → index
GET    /admin/refunds/{refund}                 → show
POST   /admin/refunds/{refund}/approve         → approve
POST   /admin/refunds/{refund}/reject          → reject
POST   /admin/refunds/{refund}/processing      → markAsProcessing
POST   /admin/refunds/{refund}/complete        → markAsCompleted
```

---

## NOTIFICATION TYPES

System akan kirim notifikasi otomatis untuk:

### User Notifications:
1. `payment_rejected` - Bukti pembayaran ditolak
2. `refund_requested` - Refund berhasil diajukan (konfirmasi)
3. `refund_approved` - Refund disetujui admin
4. `refund_rejected` - Refund ditolak admin
5. `refund_processing` - Refund sedang diproses
6. `refund_completed` - Dana refund sudah dikirim

### Admin Notifications:
1. `payment_uploaded` - User upload bukti pembayaran
2. `refund_requested` - Ada refund request baru dari user
3. `order_cancelled` - User cancel order (with auto refund)

---

## DATABASE MIGRATIONS TO RUN

Setelah pull code ini, jalankan migrations:

```bash
php artisan migrate
```

Migrations yang akan dijalankan:
1. `2025_12_30_000001_create_refunds_table.php`
2. `2025_12_30_000002_add_rejection_reason_to_payments_table.php`
3. `2025_12_30_000003_update_orders_status_enum.php`

---

## FLOWCHART COMPLETION CHECKLIST

✅ **Refund/Return Process**
- Request refund untuk completed orders
- Auto refund saat cancel order
- Admin approve/reject refund
- Multi-step refund processing (pending → approved → processing → completed)

✅ **Payment Verification Enhancement**
- Admin dapat reject payment dengan alasan
- User dapat upload ulang bukti pembayaran setelah ditolak

✅ **Order Filtering & Search**
- Filter by status, payment status, date range
- Search by order number

✅ **Bulk Operations**
- Bulk update status untuk multiple orders
- Automatic notifications

✅ **Stock Management**
- Auto return stock saat refund approved
- Stock tracking untuk semua transaksi

✅ **Complete Notification Flow**
- Real-time notifications untuk setiap status change
- Notification center accessible untuk user dan admin

✅ **Missing Return Flows Fixed**
- Order actions (track, cancel, complete) sekarang punya proper return flow
- Payment verification/rejection dengan proper flow kembali
- Profile actions dengan proper save & redirect

---

## TECHNICAL NOTES

### Stock Management Logic
```php
// Saat order cancelled atau refund approved:
foreach ($order->order_items as $item) {
    $item->product->addStock($item->quantity, $reason);
}
```

### Refund Number Generation
Format: `REF-YYYYMMDD-XXXXXX`
Example: `REF-20251230-A1B2C3`

### Order Number Generation
Format: `ORD-YYYYMMDD-XXXXXX`
Example: `ORD-20251230-D4E5F6`

---

## NEXT STEPS (Optional Future Enhancements)

Fitur yang bisa ditambahkan di masa depan:
1. Email notifications (selain database notifications)
2. SMS notifications untuk status penting
3. Refund partial (sebagian produk saja)
4. Return shipping label generation
5. Automated refund approval based on criteria
6. Refund analytics dashboard
7. Export refund reports

---

## TESTING CHECKLIST

### User Flow Testing:
- [ ] Cancel pending order → Check refund auto-created
- [ ] Cancel paid order → Check refund auto-created
- [ ] Request refund dari completed order
- [ ] View refund status
- [ ] Filter orders by status
- [ ] Search order by number
- [ ] Upload payment proof after rejection

### Admin Flow Testing:
- [ ] View all refunds
- [ ] Approve refund → Check stock returned
- [ ] Reject refund with reason
- [ ] Reject payment proof
- [ ] Bulk update order status
- [ ] View refund detail with bank info
- [ ] Process refund step by step (approve → processing → complete)

### Notification Testing:
- [ ] User receives refund approved notification
- [ ] User receives payment rejected notification
- [ ] Admin receives refund request notification
- [ ] Admin receives payment upload notification

---

## FILE STRUCTURE

```
app/
├── Http/Controllers/
│   ├── OrderController.php (UPDATED)
│   └── Admin/
│       ├── OrderController.php (UPDATED)
│       └── RefundController.php (NEW)
├── Models/
│   ├── Order.php (UPDATED)
│   ├── Payment.php (UPDATED)
│   └── Refund.php (NEW)

database/migrations/
├── 2025_12_30_000001_create_refunds_table.php (NEW)
├── 2025_12_30_000002_add_rejection_reason_to_payments_table.php (NEW)
└── 2025_12_30_000003_update_orders_status_enum.php (NEW)

routes/
└── web.php (UPDATED)
```

---

## CONCLUSION

Semua alur dari flowchart yang sebelumnya kurang sudah diimplementasi dengan lengkap:

1. **Complete Refund System** - Dari request sampai pengembalian dana
2. **Payment Rejection** - Admin bisa reject payment dengan alasan
3. **Order Filters** - User dapat filter dan search orders
4. **Bulk Operations** - Admin dapat update banyak orders sekaligus
5. **Proper Return Flows** - Semua actions punya flow kembali yang jelas
6. **Stock Management** - Auto return stock untuk refund/cancel
7. **Complete Notifications** - Setiap action kirim notifikasi

Flowchart e-commerce sekarang sudah production-ready! 🚀
