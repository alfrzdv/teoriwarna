<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Order
 * 
 * @property int $id
 * @property int $user_id
 * @property int $address_id
 * @property string $order_code
 * @property decimal $total_price
 * @property string $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User $user
 * @property UserAddress $address
 * @property Collection|OrderItem[] $order_items
 * @property Payment|null $payment
 *
 * @package App\Models
 */
class Order extends Model
{
    protected $table = 'orders';

    protected $casts = [
        'user_id' => 'int',
        'address_id' => 'int',
        'total_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2'
    ];

    protected $fillable = [
        'user_id',
        'address_id',
        'order_number',
        'total_amount',
        'subtotal',
        'shipping_name',
        'shipping_phone',
        'shipping_address',
        'shipping_city',
        'shipping_postal_code',
        'shipping_cost',
        'shipping_method',
        'tracking_number',
        'shipping_courier',
        'notes',
        'status'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->belongsTo(UserAddress::class, 'address_id');
    }

    public function order_items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function refund()
    {
        return $this->hasOne(Refund::class);
    }

    // Helper Methods
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isPaid()
    {
        return $this->status === 'paid';
    }

    public function isProcessing()
    {
        return $this->status === 'processing';
    }

    public function isShipped()
    {
        return $this->status === 'shipped';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    public function canBeCancelled()
    {
        return in_array($this->status, ['pending', 'paid']);
    }

    public function canRequestRefund()
    {
        // Can request refund if order is completed, shipped, or processing and no existing refund
        return in_array($this->status, ['completed', 'shipped', 'processing'])
            && !$this->refund;
    }

    public function hasRefund()
    {
        return $this->refund !== null;
    }

    public function getTotalItems()
    {
        return $this->order_items->sum('quantity');
    }

    public function markAsPaid()
    {
        $this->update(['status' => 'paid']);
    }

    public function markAsProcessing()
    {
        $this->update(['status' => 'processing']);
    }

    public function markAsShipped()
    {
        $this->update(['status' => 'shipped']);
    }

    public function markAsCompleted()
    {
        $this->update(['status' => 'completed']);
    }

    public function markAsCancelled()
    {
        if ($this->canBeCancelled()) {
            $this->update(['status' => 'cancelled']);
            return true;
        }
        return false;
    }

    public static function generateOrderCode()
    {
        $date = now()->format('Ymd');
        $random = strtoupper(substr(md5(uniqid()), 0, 6));
        return "ORD-{$date}-{$random}";
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    public function scopeShipped($query)
    {
        return $query->where('status', 'shipped');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}