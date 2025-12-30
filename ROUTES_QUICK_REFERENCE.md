# Routes Quick Reference

## 🆕 NEW ROUTES - User Side

### Refund & Return
```php
// Request refund untuk order yang sudah completed/shipped
POST   /orders/{order}/request-refund
Body: {
    "reason": "Barang rusak/tidak sesuai/dll",
    "refund_method": "bank_transfer|e_wallet|store_credit",
    "bank_name": "required if bank_transfer",
    "account_number": "required if bank_transfer",
    "account_name": "required if bank_transfer",
    "e_wallet_number": "required if e_wallet"
}

// Lihat status refund
GET    /orders/{order}/refund
```

### Order Filtering (UPDATED)
```php
// Index orders with filters
GET    /orders?status=pending&payment_status=paid&search=ORD-123&date_from=2025-01-01&date_to=2025-12-31
```

---

## 🆕 NEW ROUTES - Admin Side

### Payment Management
```php
// Reject payment proof (existing verify masih ada)
POST   /admin/orders/{order}/reject-payment
Body: {
    "rejection_reason": "Bukti pembayaran tidak jelas/tidak valid"
}

// Bulk update status untuk multiple orders
POST   /admin/orders/bulk-update-status
Body: {
    "order_ids": [1, 2, 3, 4],
    "status": "processing|shipped|completed|cancelled"
}
```

### Refund Management (COMPLETELY NEW)
```php
// List all refunds with filters
GET    /admin/refunds?status=pending&search=REF-123

// View refund detail
GET    /admin/refunds/{refund}

// Approve refund (will return stock automatically)
POST   /admin/refunds/{refund}/approve

// Reject refund
POST   /admin/refunds/{refund}/reject
Body: {
    "admin_notes": "Alasan penolakan"
}

// Mark refund as processing (sedang transfer dana)
POST   /admin/refunds/{refund}/processing

// Mark refund as completed (dana sudah dikirim)
POST   /admin/refunds/{refund}/complete
```

---

## 📋 COMPLETE ORDER FLOW

### USER SIDE

1. **Browse & Add to Cart**
   ```
   GET    /products
   GET    /products/{product}
   POST   /cart/add/{product}
   ```

2. **Checkout**
   ```
   GET    /checkout
   POST   /checkout/apply-coupon
   POST   /checkout/remove-coupon
   POST   /checkout/process
   ```

3. **Order Management**
   ```
   GET    /orders (with filters)
   GET    /orders/{order}
   POST   /orders/{order}/upload-payment
   POST   /orders/{order}/cancel (auto create refund if paid)
   POST   /orders/{order}/complete
   POST   /orders/{order}/request-refund (NEW)
   GET    /orders/{order}/refund (NEW)
   ```

### ADMIN SIDE

1. **Order Management**
   ```
   GET    /admin/orders (with filters)
   GET    /admin/orders/{order}
   POST   /admin/orders/{order}/update-status
   POST   /admin/orders/{order}/add-tracking
   POST   /admin/orders/{order}/verify-payment
   POST   /admin/orders/{order}/reject-payment (NEW)
   POST   /admin/orders/bulk-update-status (NEW)
   ```

2. **Refund Management** (COMPLETELY NEW)
   ```
   GET    /admin/refunds
   GET    /admin/refunds/{refund}
   POST   /admin/refunds/{refund}/approve
   POST   /admin/refunds/{refund}/reject
   POST   /admin/refunds/{refund}/processing
   POST   /admin/refunds/{refund}/complete
   ```

---

## 🔄 STATUS FLOW

### Order Status
```
pending → paid → processing → shipped → completed
   ↓        ↓         ↓          ↓
cancelled → refunded (NEW)
```

### Payment Status
```
pending → pending_verification → paid
   ↓              ↓
rejected (NEW) → cancelled
```

### Refund Status (NEW)
```
pending → approved → processing → completed
   ↓
rejected
```

---

## 🔔 NOTIFICATION TRIGGERS

### Auto-sent to USER:
- `payment_uploaded` - Ketika user upload payment proof
- `payment_verified` - Admin verify payment
- `payment_rejected` - Admin reject payment (NEW)
- `order_status` - Order status changed
- `order_shipped` - Order dikirim dengan tracking
- `refund_approved` - Refund disetujui (NEW)
- `refund_rejected` - Refund ditolak (NEW)
- `refund_processing` - Refund sedang diproses (NEW)
- `refund_completed` - Refund selesai (NEW)

### Auto-sent to ADMIN:
- `new_order` - Ada order baru
- `payment_uploaded` - User upload payment proof
- `refund_requested` - User request refund (NEW)

---

## 🎯 MIDDLEWARE

### Auth Required
```php
middleware(['auth'])
- Cart, Checkout, Orders, Profile, Addresses
```

### Email Verified Required
```php
middleware(['auth', 'verified'])
- Checkout process
- Buy now process
- Create order
```

### Admin Only
```php
middleware(['auth', 'admin'])
- All /admin/* routes
```

---

## 💡 QUICK TIPS

### For Frontend Developers:

**Order List Page:**
```html
<!-- Filter form -->
<form action="/orders" method="GET">
    <select name="status">
        <option value="all">All Status</option>
        <option value="pending">Pending</option>
        <option value="paid">Paid</option>
        <option value="processing">Processing</option>
        <option value="shipped">Shipped</option>
        <option value="completed">Completed</option>
        <option value="cancelled">Cancelled</option>
        <option value="refunded">Refunded</option>
    </select>

    <input type="text" name="search" placeholder="Search order number">
    <input type="date" name="date_from">
    <input type="date" name="date_to">
    <button type="submit">Filter</button>
</form>
```

**Order Detail Page:**
```html
<!-- Cancel Order with Refund -->
<form action="/orders/{{ order.id }}/cancel" method="POST">
    @csrf
    <textarea name="cancel_reason" placeholder="Alasan cancel"></textarea>
    <button>Cancel & Request Refund</button>
</form>

<!-- Request Refund (for completed orders) -->
<form action="/orders/{{ order.id }}/request-refund" method="POST">
    @csrf
    <textarea name="reason" required></textarea>
    <select name="refund_method" required>
        <option value="bank_transfer">Bank Transfer</option>
        <option value="e_wallet">E-Wallet</option>
        <option value="store_credit">Store Credit</option>
    </select>

    <!-- Show these fields conditionally -->
    <div id="bank-fields" style="display:none">
        <input name="bank_name" placeholder="Nama Bank">
        <input name="account_number" placeholder="No Rekening">
        <input name="account_name" placeholder="Nama Pemilik">
    </div>

    <div id="ewallet-fields" style="display:none">
        <input name="e_wallet_number" placeholder="No E-Wallet">
    </div>

    <button>Submit Refund Request</button>
</form>
```

**Admin - Bulk Update:**
```html
<form action="/admin/orders/bulk-update-status" method="POST">
    @csrf
    <input type="checkbox" name="order_ids[]" value="1">
    <input type="checkbox" name="order_ids[]" value="2">
    <input type="checkbox" name="order_ids[]" value="3">

    <select name="status">
        <option value="processing">Processing</option>
        <option value="shipped">Shipped</option>
        <option value="completed">Completed</option>
    </select>

    <button>Update Selected</button>
</form>
```

**Admin - Payment Actions:**
```html
<!-- Verify Payment -->
<form action="/admin/orders/{{ order.id }}/verify-payment" method="POST">
    @csrf
    <button class="btn-success">Verify Payment</button>
</form>

<!-- Reject Payment -->
<form action="/admin/orders/{{ order.id }}/reject-payment" method="POST">
    @csrf
    <textarea name="rejection_reason" required
              placeholder="Alasan reject (misal: foto tidak jelas)">
    </textarea>
    <button class="btn-danger">Reject Payment</button>
</form>
```

**Admin - Refund Management:**
```html
<!-- Approve Refund -->
<form action="/admin/refunds/{{ refund.id }}/approve" method="POST">
    @csrf
    <button>Approve Refund</button>
</form>

<!-- Reject Refund -->
<form action="/admin/refunds/{{ refund.id }}/reject" method="POST">
    @csrf
    <textarea name="admin_notes" required></textarea>
    <button>Reject Refund</button>
</form>

<!-- Process Refund (after approved) -->
<form action="/admin/refunds/{{ refund.id }}/processing" method="POST">
    @csrf
    <button>Mark as Processing</button>
</form>

<!-- Complete Refund (after transfer sent) -->
<form action="/admin/refunds/{{ refund.id }}/complete" method="POST">
    @csrf
    <button>Mark as Completed</button>
</form>
```

---

## 🚀 API Response Examples

### Success Response:
```json
{
    "success": "Refund berhasil diajukan."
}
// Redirect with flash message
```

### Error Response:
```json
{
    "error": "Pesanan ini tidak dapat di-refund."
}
// Redirect back with error message
```

### Validation Error:
```json
{
    "errors": {
        "reason": ["The reason field is required."],
        "refund_method": ["The refund method field is required."]
    }
}
```

---

## 📊 Model Relationships

```php
// Order
$order->refund             // hasOne Refund (NEW)
$order->payment            // hasOne Payment
$order->order_items        // hasMany OrderItem
$order->user               // belongsTo User

// Refund (NEW)
$refund->order             // belongsTo Order
$refund->user              // belongsTo User
$refund->approver          // belongsTo User (admin who approved)

// Payment
$payment->order            // belongsTo Order
$payment->rejection_reason // string (NEW)
```

---

## 🔍 Helper Methods

### Order Model
```php
$order->canBeCancelled()      // bool - can cancel?
$order->canRequestRefund()    // bool - can request refund? (NEW)
$order->hasRefund()           // bool - already has refund? (NEW)
```

### Refund Model (NEW)
```php
$refund->isPending()
$refund->isApproved()
$refund->isRejected()
$refund->isProcessing()
$refund->isCompleted()
$refund->markAsApproved($adminId)
$refund->markAsRejected($adminNotes)
$refund->markAsProcessing()
$refund->markAsCompleted()
```

### Payment Model
```php
$payment->isPending()
$payment->isSuccess()
$payment->isFailed()
```
