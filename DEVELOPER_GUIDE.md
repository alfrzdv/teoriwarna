# Developer Guide - Flowchart Implementation

## 🎯 Quick Start

Ini adalah implementasi lengkap dari flowchart e-commerce dengan semua fitur refund, payment verification, dan order management yang diperlukan.

### What's New?
1. **Complete Refund System** - User bisa request refund, admin review & process
2. **Payment Rejection** - Admin bisa tolak payment proof dengan alasan
3. **Order Filters** - User bisa filter orders by status, date, dll
4. **Bulk Operations** - Admin update banyak orders sekaligus
5. **Auto Stock Return** - Stock otomatis kembali saat refund/cancel

---

## 📁 File Structure

### New Files
```
app/
├── Models/
│   └── Refund.php                           # NEW - Refund model
├── Http/Controllers/
│   └── Admin/
│       └── RefundController.php             # NEW - Admin refund management

database/migrations/
├── 2025_12_30_000001_create_refunds_table.php          # NEW
├── 2025_12_30_000002_add_rejection_reason_to_payments_table.php  # NEW
└── 2025_12_30_000003_update_orders_status_enum.php     # NEW

Documentation/
├── FLOWCHART_IMPLEMENTATION.md              # Complete implementation guide
├── ROUTES_QUICK_REFERENCE.md                # Routes reference
├── CHANGELOG.md                             # Version history
└── DEVELOPER_GUIDE.md                       # This file
```

### Modified Files
```
app/
├── Http/Controllers/
│   ├── OrderController.php                  # Added refund features
│   └── Admin/
│       └── OrderController.php              # Added reject payment & bulk update
├── Models/
│   ├── Order.php                            # Added refund relationship
│   └── Payment.php                          # Added rejection_reason

routes/
└── web.php                                  # Added new routes
```

---

## 🔧 Installation & Setup

### 1. Pull Latest Code
```bash
git checkout youthful-payne
git pull origin youthful-payne
```

### 2. Install Dependencies (if needed)
```bash
composer install
```

### 3. Run Migrations
```bash
php artisan migrate
```

Expected output:
```
Migrating: 2025_12_30_000001_create_refunds_table
Migrated:  2025_12_30_000001_create_refunds_table
Migrating: 2025_12_30_000002_add_rejection_reason_to_payments_table
Migrated:  2025_12_30_000002_add_rejection_reason_to_payments_table
Migrating: 2025_12_30_000003_update_orders_status_enum
Migrated:  2025_12_30_000003_update_orders_status_enum
```

### 4. Clear Cache
```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### 5. Verify Routes
```bash
php artisan route:list | grep refund
```

Should show:
```
POST   /orders/{order}/request-refund
GET    /orders/{order}/refund
GET    /admin/refunds
POST   /admin/refunds/{refund}/approve
POST   /admin/refunds/{refund}/reject
...etc
```

---

## 💻 Development Workflow

### Working with Refunds

#### 1. User Requests Refund
```php
// Route: POST /orders/{order}/request-refund

// Frontend form
<form action="{{ route('orders.request-refund', $order) }}" method="POST">
    @csrf
    <textarea name="reason" required>Barang rusak</textarea>

    <select name="refund_method" required>
        <option value="bank_transfer">Bank Transfer</option>
        <option value="e_wallet">E-Wallet</option>
        <option value="store_credit">Store Credit</option>
    </select>

    <!-- Conditional fields based on refund_method -->
    <div class="bank-fields">
        <input name="bank_name" placeholder="BCA">
        <input name="account_number" placeholder="1234567890">
        <input name="account_name" placeholder="John Doe">
    </div>

    <button type="submit">Submit Refund Request</button>
</form>
```

#### 2. Admin Reviews Refund
```php
// Route: GET /admin/refunds

// Controller method
public function index(Request $request)
{
    $query = Refund::with(['order', 'user'])->latest();

    if ($request->status) {
        $query->where('status', $request->status);
    }

    $refunds = $query->paginate(20);
    return view('admin.refunds.index', compact('refunds'));
}
```

#### 3. Admin Approves Refund
```php
// Route: POST /admin/refunds/{refund}/approve

// What happens:
1. Refund status → 'approved'
2. Stock automatically returned to inventory
3. Order status → 'refunded'
4. Notification sent to user
5. Approver recorded (current admin)
```

#### 4. Admin Processes Refund
```php
// Route: POST /admin/refunds/{refund}/processing
// Admin sedang transfer dana

// Route: POST /admin/refunds/{refund}/complete
// Dana sudah dikirim ke user
```

### Working with Payment Rejection

#### Admin Rejects Payment
```php
// Route: POST /admin/orders/{order}/reject-payment

<form action="{{ route('admin.orders.reject-payment', $order) }}" method="POST">
    @csrf
    <textarea name="rejection_reason" required>
        Bukti pembayaran tidak jelas, mohon upload ulang dengan foto yang lebih jelas
    </textarea>
    <button type="submit">Reject Payment</button>
</form>

// What happens:
1. Payment status → 'rejected'
2. rejection_reason saved to database
3. User gets notification with reason
4. User can upload new proof
```

### Working with Order Filters

#### User Filters Orders
```php
// Route: GET /orders

// Example URLs:
/orders                                           // All orders
/orders?status=pending                            // Only pending
/orders?status=completed&date_from=2025-01-01    // Completed from Jan 1
/orders?search=ORD-123                           // Search by order number
/orders?payment_status=paid&status=processing    // Multiple filters

// Frontend form
<form action="{{ route('orders.index') }}" method="GET">
    <select name="status">
        <option value="all">All Status</option>
        <option value="pending">Pending</option>
        <option value="processing">Processing</option>
        <option value="shipped">Shipped</option>
        <option value="completed">Completed</option>
        <option value="cancelled">Cancelled</option>
        <option value="refunded">Refunded</option>
    </select>

    <input type="date" name="date_from" placeholder="From">
    <input type="date" name="date_to" placeholder="To">
    <input type="text" name="search" placeholder="Order Number">

    <button type="submit">Filter</button>
</form>
```

### Working with Bulk Operations

#### Admin Bulk Update
```php
// Route: POST /admin/orders/bulk-update-status

<form action="{{ route('admin.orders.bulk-update-status') }}" method="POST">
    @csrf

    <!-- Checkboxes for orders -->
    @foreach($orders as $order)
        <input type="checkbox" name="order_ids[]" value="{{ $order->id }}">
        {{ $order->order_number }}
    @endforeach

    <!-- New status -->
    <select name="status" required>
        <option value="processing">Processing</option>
        <option value="shipped">Shipped</option>
        <option value="completed">Completed</option>
    </select>

    <button type="submit">Update Selected Orders</button>
</form>

// JavaScript untuk select all
<script>
    document.getElementById('select-all').addEventListener('change', function() {
        document.querySelectorAll('input[name="order_ids[]"]')
            .forEach(cb => cb.checked = this.checked);
    });
</script>
```

---

## 🎨 Frontend Integration

### Blade Templates Structure

Recommended structure:
```
resources/views/
├── orders/
│   ├── index.blade.php          # Order list with filters
│   ├── show.blade.php            # Order detail
│   └── refund.blade.php          # NEW - Refund status view
├── admin/
│   ├── orders/
│   │   ├── index.blade.php       # With bulk update
│   │   └── show.blade.php        # With reject payment button
│   └── refunds/                  # NEW
│       ├── index.blade.php       # Refund list
│       └── show.blade.php        # Refund detail with approve/reject
```

### Key UI Components

#### 1. Refund Request Button (User Order Detail)
```blade
@if($order->canRequestRefund())
    <button type="button" data-bs-toggle="modal" data-bs-target="#refundModal">
        Request Refund/Return
    </button>

    <!-- Modal with form -->
    <div class="modal" id="refundModal">
        <form action="{{ route('orders.request-refund', $order) }}" method="POST">
            @csrf
            <!-- Form fields here -->
        </form>
    </div>
@endif

@if($order->hasRefund())
    <a href="{{ route('orders.refund', $order) }}" class="btn btn-info">
        View Refund Status
    </a>
@endif
```

#### 2. Cancel Order Button (with auto refund)
```blade
@if($order->canBeCancelled())
    <form action="{{ route('orders.cancel', $order) }}" method="POST"
          onsubmit="return confirm('Cancel order ini? Refund akan otomatis diproses jika sudah bayar.')">
        @csrf
        <textarea name="cancel_reason" placeholder="Alasan cancel (optional)"></textarea>
        <button type="submit" class="btn btn-danger">Cancel Order</button>
    </form>
@endif
```

#### 3. Payment Proof Actions (Admin)
```blade
@if($order->payment && $order->payment->status === 'pending_verification')
    <div class="payment-actions">
        <!-- Verify -->
        <form action="{{ route('admin.orders.verify-payment', $order) }}" method="POST" style="display:inline">
            @csrf
            <button class="btn btn-success">Verify Payment</button>
        </form>

        <!-- Reject -->
        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
            Reject Payment
        </button>

        <!-- Reject Modal -->
        <div class="modal" id="rejectModal">
            <form action="{{ route('admin.orders.reject-payment', $order) }}" method="POST">
                @csrf
                <textarea name="rejection_reason" required
                          placeholder="Alasan reject (akan dikirim ke user)"></textarea>
                <button type="submit">Confirm Reject</button>
            </form>
        </div>
    </div>
@endif
```

#### 4. Refund Management (Admin)
```blade
<!-- Refund List -->
@foreach($refunds as $refund)
    <tr>
        <td>{{ $refund->refund_number }}</td>
        <td>{{ $refund->user->name }}</td>
        <td>Rp {{ number_format($refund->refund_amount) }}</td>
        <td>
            <span class="badge bg-{{ $refund->status === 'pending' ? 'warning' : 'info' }}">
                {{ ucfirst($refund->status) }}
            </span>
        </td>
        <td>
            <a href="{{ route('admin.refunds.show', $refund) }}" class="btn btn-sm btn-primary">
                Detail
            </a>
        </td>
    </tr>
@endforeach

<!-- Refund Detail Actions -->
@if($refund->isPending())
    <form action="{{ route('admin.refunds.approve', $refund) }}" method="POST" style="display:inline">
        @csrf
        <button class="btn btn-success">Approve Refund</button>
    </form>

    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectRefundModal">
        Reject Refund
    </button>
@endif

@if($refund->isApproved())
    <form action="{{ route('admin.refunds.processing', $refund) }}" method="POST">
        @csrf
        <button class="btn btn-info">Mark as Processing</button>
    </form>
@endif

@if($refund->isProcessing())
    <form action="{{ route('admin.refunds.complete', $refund) }}" method="POST">
        @csrf
        <button class="btn btn-success">Mark as Completed</button>
    </form>
@endif
```

#### 5. Bulk Update Orders (Admin)
```blade
<form action="{{ route('admin.orders.bulk-update-status') }}" method="POST" id="bulkForm">
    @csrf

    <!-- Select All -->
    <input type="checkbox" id="select-all"> Select All

    <!-- Order Checkboxes -->
    <table>
        @foreach($orders as $order)
            <tr>
                <td>
                    <input type="checkbox" name="order_ids[]" value="{{ $order->id }}" class="order-checkbox">
                </td>
                <td>{{ $order->order_number }}</td>
                <td>{{ $order->user->name }}</td>
                <td>{{ $order->status }}</td>
            </tr>
        @endforeach
    </table>

    <!-- Bulk Actions -->
    <div class="bulk-actions" style="display:none">
        <select name="status" required>
            <option value="">-- Select New Status --</option>
            <option value="processing">Processing</option>
            <option value="shipped">Shipped</option>
            <option value="completed">Completed</option>
        </select>
        <button type="submit">Update Selected</button>
    </div>
</form>

<script>
    // Show bulk actions when checkbox selected
    document.querySelectorAll('.order-checkbox').forEach(cb => {
        cb.addEventListener('change', function() {
            const anyChecked = document.querySelectorAll('.order-checkbox:checked').length > 0;
            document.querySelector('.bulk-actions').style.display = anyChecked ? 'block' : 'none';
        });
    });

    // Select all functionality
    document.getElementById('select-all').addEventListener('change', function() {
        document.querySelectorAll('.order-checkbox').forEach(cb => cb.checked = this.checked);
        document.querySelector('.bulk-actions').style.display = this.checked ? 'block' : 'none';
    });
</script>
```

---

## 🧪 Testing Guide

### Manual Testing Checklist

#### Refund Flow
- [ ] User request refund dengan bank transfer
- [ ] User request refund dengan e-wallet
- [ ] User request refund dengan store credit
- [ ] Admin approve refund → Check stock bertambah
- [ ] Admin reject refund → User dapat notifikasi
- [ ] Approved refund → Mark as processing
- [ ] Processing refund → Mark as completed
- [ ] Cancel order otomatis create refund (jika paid)

#### Payment Flow
- [ ] User upload payment proof
- [ ] Admin verify payment → Order jadi processing
- [ ] Admin reject payment → User dapat notif + alasan
- [ ] User upload ulang payment proof setelah ditolak

#### Order Management
- [ ] Filter orders by status
- [ ] Filter orders by payment status
- [ ] Search order by number
- [ ] Filter by date range
- [ ] Combine multiple filters

#### Bulk Operations
- [ ] Select multiple orders
- [ ] Bulk update to processing
- [ ] Bulk update to shipped
- [ ] Check semua user dapat notifikasi

### Unit Testing (Recommended)

Create test file: `tests/Feature/RefundTest.php`

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\Refund;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RefundTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_request_refund()
    {
        $user = User::factory()->create();
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'status' => 'completed'
        ]);

        $response = $this->actingAs($user)->post(route('orders.request-refund', $order), [
            'reason' => 'Barang rusak',
            'refund_method' => 'bank_transfer',
            'bank_name' => 'BCA',
            'account_number' => '1234567890',
            'account_name' => 'John Doe'
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('refunds', [
            'order_id' => $order->id,
            'status' => 'pending'
        ]);
    }

    public function test_admin_can_approve_refund()
    {
        $admin = User::factory()->admin()->create();
        $refund = Refund::factory()->create(['status' => 'pending']);

        $response = $this->actingAs($admin)
            ->post(route('admin.refunds.approve', $refund));

        $response->assertRedirect();
        $this->assertDatabaseHas('refunds', [
            'id' => $refund->id,
            'status' => 'approved'
        ]);
    }
}
```

---

## 🔍 Debugging Tips

### Common Issues & Solutions

#### 1. Migration Error
```bash
Error: SQLSTATE[42S01]: Base table or view already exists
```
**Solution**:
```bash
php artisan migrate:rollback
php artisan migrate
```

#### 2. Route Not Found
```bash
Error: Route [orders.request-refund] not defined
```
**Solution**:
```bash
php artisan route:clear
php artisan route:cache
```

#### 3. Refund Not Creating
**Check**:
- Order status (must be completed/shipped/processing)
- Order doesn't already have refund
- User owns the order

**Debug**:
```php
// Add to controller
dd([
    'can_request' => $order->canRequestRefund(),
    'has_refund' => $order->hasRefund(),
    'status' => $order->status
]);
```

#### 4. Stock Not Returning
**Check**:
- Product stock before refund
- Product stock after refund
- product_stocks table entries

**Debug**:
```php
// In RefundController@approve
Log::info('Stock before:', ['stock' => $product->getCurrentStock()]);
$product->addStock($quantity, $reason);
Log::info('Stock after:', ['stock' => $product->getCurrentStock()]);
```

---

## 📊 Database Queries

### Useful Queries for Debugging

```sql
-- Check all refunds for a user
SELECT r.*, o.order_number, u.name
FROM refunds r
JOIN orders o ON r.order_id = o.id
JOIN users u ON r.user_id = u.id
WHERE u.id = 1;

-- Check refunds by status
SELECT status, COUNT(*) as count, SUM(refund_amount) as total_amount
FROM refunds
GROUP BY status;

-- Check orders with refunds
SELECT o.order_number, o.status, r.refund_number, r.status as refund_status
FROM orders o
LEFT JOIN refunds r ON o.id = r.order_id
WHERE r.id IS NOT NULL;

-- Check payment rejections
SELECT o.order_number, p.status, p.rejection_reason
FROM orders o
JOIN payments p ON o.id = p.order_id
WHERE p.status = 'rejected';

-- Check stock movements
SELECT ps.*, p.name
FROM product_stocks ps
JOIN products p ON ps.product_id = p.id
WHERE ps.note LIKE '%Refund%'
ORDER BY ps.created_at DESC;
```

---

## 🚀 Performance Tips

### Optimization Recommendations

#### 1. Eager Loading
```php
// Good - Load relationships upfront
$refunds = Refund::with(['order.order_items.product', 'user'])->paginate(20);

// Bad - N+1 query problem
$refunds = Refund::all();
foreach ($refunds as $refund) {
    echo $refund->order->order_number; // Extra query each time!
}
```

#### 2. Query Optimization
```php
// Filter dengan index
$orders = Order::where('status', 'pending') // status is indexed
    ->where('user_id', Auth::id())           // user_id is indexed
    ->latest()
    ->paginate(10);
```

#### 3. Caching (Optional)
```php
// Cache refund statistics
$stats = Cache::remember('refund-stats', 3600, function () {
    return [
        'pending' => Refund::pending()->count(),
        'approved' => Refund::approved()->count(),
        'total_amount' => Refund::sum('refund_amount')
    ];
});
```

---

## 📞 Support & Questions

### Documentation Files
1. `FLOWCHART_IMPLEMENTATION.md` - Complete implementation details
2. `ROUTES_QUICK_REFERENCE.md` - All routes & examples
3. `CHANGELOG.md` - Version history
4. `DEVELOPER_GUIDE.md` - This file

### Need Help?
1. Check documentation files above
2. Review testing checklist
3. Check debugging tips
4. Review example code snippets

---

**Happy Coding! 🎉**
