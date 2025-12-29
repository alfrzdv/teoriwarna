<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Complaint
 * 
 * @property int $id
 * @property int $user_id
 * @property int $order_id
 * @property string $reason
 * @property string $description
 * @property string $status (open/in_review/resolved/rejected)
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User $user
 * @property Order $order
 *
 * @package App\Models
 */
class Complaint extends Model
{
    protected $table = 'complaints';

    protected $casts = [
        'user_id' => 'int',
        'order_id' => 'int'
    ];

    protected $fillable = [
        'user_id',
        'order_id',
        'reason',
        'description',
        'status',
        'admin_reply',
        'admin_id',
        'resolved_at',
    ];

    protected $casts = [
        'user_id' => 'int',
        'order_id' => 'int',
        'admin_id' => 'int',
        'resolved_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    // Helper Methods
    public function isOpen()
    {
        return $this->status === 'open';
    }

    public function isInReview()
    {
        return $this->status === 'in_review';
    }

    public function isResolved()
    {
        return $this->status === 'resolved';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    public function markAsInReview()
    {
        $this->update(['status' => 'in_review']);
    }

    public function markAsResolved()
    {
        $this->update([
            'status' => 'resolved',
            'resolved_at' => now(),
        ]);
    }

    public function markAsRejected()
    {
        $this->update(['status' => 'rejected']);
    }

    public function replyByAdmin($adminId, $reply)
    {
        $this->update([
            'admin_id' => $adminId,
            'admin_reply' => $reply,
            'status' => 'in_review',
        ]);
    }

    public function hasReply()
    {
        return !empty($this->admin_reply);
    }

    // Scopes
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeInReview($query)
    {
        return $query->where('status', 'in_review');
    }

    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByOrder($query, $orderId)
    {
        return $query->where('order_id', $orderId);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}