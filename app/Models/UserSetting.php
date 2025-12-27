<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UserSetting
 * 
 * @property int $id
 * @property int $user_id
 * @property bool $notification_enabled
 * @property bool $promo_enabled
 * 
 * @property User $user
 *
 * @package App\Models
 */
class UserSetting extends Model
{
    protected $table = 'user_settings';
    
    public $timestamps = false;

    protected $casts = [
        'user_id' => 'int',
        'notification_enabled' => 'bool',
        'promo_enabled' => 'bool'
    ];

    protected $fillable = [
        'user_id',
        'notification_enabled',
        'promo_enabled'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper Methods
    public function enableNotifications()
    {
        $this->update(['notification_enabled' => true]);
    }

    public function disableNotifications()
    {
        $this->update(['notification_enabled' => false]);
    }

    public function enablePromo()
    {
        $this->update(['promo_enabled' => true]);
    }

    public function disablePromo()
    {
        $this->update(['promo_enabled' => false]);
    }

    public function toggleNotifications()
    {
        $this->update(['notification_enabled' => !$this->notification_enabled]);
    }

    public function togglePromo()
    {
        $this->update(['promo_enabled' => !$this->promo_enabled]);
    }

    // Static Methods
    public static function getOrCreateForUser($userId)
    {
        return self::firstOrCreate(
            ['user_id' => $userId],
            [
                'notification_enabled' => true,
                'promo_enabled' => true
            ]
        );
    }
}