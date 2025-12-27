<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Notification
 * 
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $message
 * @property string $type (order/promo/system)
 * @property bool $is_read
 * @property Carbon|null $created_at
 * 
 * @property User $user
 *
 * @package App\Models
 */
class Notification extends Model
{
    protected $table = 'notifications';
    
    public $timestamps = false;
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    protected $casts = [
        'user_id' => 'int',
        'is_read' => 'bool',
        'created_at' => 'datetime'
    ];

    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type',
        'is_read'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper Methods
    public function markAsRead()
    {
        if (!$this->is_read) {
            $this->update(['is_read' => true]);
        }
    }

    public function markAsUnread()
    {
        $this->update(['is_read' => false]);
    }

    public function isOrder()
    {
        return $this->type === 'order';
    }

    public function isPromo()
    {
        return $this->type === 'promo';
    }

    public function isSystem()
    {
        return $this->type === 'system';
    }

    // Static Helper Methods
    public static function createOrderNotification($userId, $title, $message)
    {
        return self::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => 'order',
            'is_read' => false
        ]);
    }

    public static function createPromoNotification($userId, $title, $message)
    {
        return self::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => 'promo',
            'is_read' => false
        ]);
    }

    public static function createSystemNotification($userId, $title, $message)
    {
        return self::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => 'system',
            'is_read' => false
        ]);
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeOrder($query)
    {
        return $query->where('type', 'order');
    }

    public function scopePromo($query)
    {
        return $query->where('type', 'promo');
    }

    public function scopeSystem($query)
    {
        return $query->where('type', 'system');
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}