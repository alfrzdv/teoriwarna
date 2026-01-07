<?php

namespace App\Models;

use Carbon\Carbon;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Class User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string|null $phone
 * @property string $role
 * @property bool $is_active
 * @property Carbon|null $last_login
 * @property Carbon|null $email_verified_at
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Collection|Cart[] $carts
 * @property Collection|Order[] $orders
 * @property Collection|UserAddress[] $user_addresses
 *
 * @package App\Models
 */
class User extends Authenticatable implements FilamentUser
{
    use Notifiable;

    protected $table = 'users';

    protected $casts = [
        'is_active' => 'bool',
        'is_banned' => 'bool',
        'last_login' => 'datetime'
    ];

    protected $hidden = [
        'password'
    ];

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role',
        'is_active',
        'is_banned',
        'last_login',
        'profile_picture'
    ];

    // Relationships
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function user_addresses()
    {
        return $this->hasMany(UserAddress::class);
    }

    // Helper Methods
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isUser()
    {
        return $this->role === 'user';
    }

    public function hasAdminAccess()
    {
        return $this->role === 'admin';
    }

    public function isActive()
    {
        return $this->is_active;
    }

    public function getDefaultAddress()
    {
        return $this->user_addresses()->where('is_default', true)->first();
    }

    public function updateLastLogin()
    {
        $this->update(['last_login' => now()]);
    }

    public function getProfilePictureUrl()
    {
        if ($this->profile_picture) {
            return asset('storage/' . $this->profile_picture);
        }
        return null;
    }

    public function getProfilePictureThumbnailUrl()
    {
        if ($this->profile_picture) {
            return \App\Helpers\ImageHelper::getThumbnailUrl($this->profile_picture);
        }
        return null;
    }

    public function getInitials()
    {
        return strtoupper(substr($this->name, 0, 1));
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeUsers($query)
    {
        return $query->where('role', 'user');
    }

    // Filament Panel Access Control
    public function canAccessPanel(Panel $panel): bool
    {
        // Only admin role can access the admin panel
        return $this->hasAdminAccess() && $this->isActive();
    }
}