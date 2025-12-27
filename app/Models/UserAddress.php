<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserAddress
 * 
 * @property int $id
 * @property int $user_id
 * @property string $recipient_name
 * @property string $phone
 * @property string $address
 * @property string $city
 * @property string $province
 * @property string $postal_code
 * @property bool $is_default
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User $user
 *
 * @package App\Models
 */
class UserAddress extends Model
{
    protected $table = 'user_addresses';

    protected $casts = [
        'user_id' => 'int',
        'is_default' => 'bool'
    ];

    protected $fillable = [
        'user_id',
        'recipient_name',
        'phone',
        'address',
        'city',
        'province',
        'postal_code',
        'is_default'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper Methods
    public function getFullAddress()
    {
        $parts = [
            $this->address,
            $this->city,
            $this->province,
            $this->postal_code
        ];

        return implode(', ', array_filter($parts));
    }

    public function makeDefault()
    {
        // Set all other addresses as non-default
        UserAddress::where('user_id', $this->user_id)
            ->where('id', '!=', $this->id)
            ->update(['is_default' => false]);

        // Set this address as default
        $this->update(['is_default' => true]);
    }

    // Scopes
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Events
    protected static function boot()
    {
        parent::boot();

        // Set as default if it's the first address
        static::creating(function ($address) {
            if (!UserAddress::where('user_id', $address->user_id)->exists()) {
                $address->is_default = true;
            }
        });
    }
}