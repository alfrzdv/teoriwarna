<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Payment
 * 
 * @property int $id
 * @property int $order_id
 * @property string $method
 * @property string $status
 * @property Carbon|null $payment_date
 * @property string|null $proof
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Order $order
 *
 * @package App\Models
 */
class Payment extends Model
{
    protected $table = 'payments';

    protected $casts = [
        'order_id' => 'int',
        'payment_date' => 'datetime'
    ];

    protected $fillable = [
        'order_id',
        'method',
        'status',
        'payment_date',
        'proof'
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Helper Methods
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isSuccess()
    {
        return $this->status === 'success';
    }

    public function isFailed()
    {
        return $this->status === 'failed';
    }

    public function isTransfer()
    {
        return $this->method === 'transfer';
    }

    public function isEwallet()
    {
        return $this->method === 'ewallet';
    }

    public function isCOD()
    {
        return $this->method === 'cod';
    }

    public function markAsSuccess()
    {
        $this->update([
            'status' => 'success',
            'payment_date' => now()
        ]);

        // Update order status
        $this->order->markAsPaid();
    }

    public function markAsFailed()
    {
        $this->update(['status' => 'failed']);
    }

    public function uploadProof($proofPath)
    {
        $this->update(['proof' => $proofPath]);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeSuccess($query)
    {
        return $query->where('status', 'success');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeByMethod($query, $method)
    {
        return $query->where('method', $method);
    }
}