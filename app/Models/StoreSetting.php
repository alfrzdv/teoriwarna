<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * Class StoreSetting
 * 
 * @property int $id
 * @property string $store_name
 * @property string $address
 * @property string $email
 * @property string $phone
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class StoreSetting extends Model
{
    protected $table = 'store_settings';

    protected $fillable = [
        'store_name',
        'logo',
        'description',
        'address',
        'email',
        'phone',
        'whatsapp',
        'instagram',
        'facebook',
        'bank_name',
        'bank_account_number',
        'bank_account_name',
        'business_hours',
        'meta_keywords',
        'meta_description'
    ];

    // Helper Methods
    public static function get()
    {
        return Cache::remember('store_settings', 3600, function () {
            return self::first() ?? self::create([
                'store_name' => config('app.name', 'Teori Warna Store'),
                'address' => '',
                'email' => '',
                'phone' => ''
            ]);
        });
    }

    public static function updateSettings($data)
    {
        $setting = self::first();
        
        if ($setting) {
            $setting->update($data);
        } else {
            $setting = self::create($data);
        }

        Cache::forget('store_settings');
        return $setting;
    }

    // Accessor Methods
    public static function getStoreName()
    {
        return self::get()->store_name;
    }

    public static function getAddress()
    {
        return self::get()->address;
    }

    public static function getEmail()
    {
        return self::get()->email;
    }

    public static function getPhone()
    {
        return self::get()->phone;
    }

    // Events
    protected static function boot()
    {
        parent::boot();

        // Clear cache on update
        static::saved(function () {
            Cache::forget('store_settings');
        });
    }
}