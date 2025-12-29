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
        'meta_description',
        // Payment Gateway
        'midtrans_enabled',
        'midtrans_server_key',
        'midtrans_client_key',
        'midtrans_is_production',
        'bank_transfer_enabled',
        'e_wallet_enabled',
        'cod_enabled',
        // Shipping
        'shipping_regular_cost',
        'shipping_regular_name',
        'shipping_regular_estimation',
        'shipping_express_cost',
        'shipping_express_name',
        'shipping_express_estimation',
        'shipping_sameday_cost',
        'shipping_sameday_name',
        'shipping_sameday_estimation',
        'free_shipping_enabled',
        'free_shipping_minimum'
    ];

    protected $casts = [
        'midtrans_enabled' => 'boolean',
        'midtrans_is_production' => 'boolean',
        'bank_transfer_enabled' => 'boolean',
        'e_wallet_enabled' => 'boolean',
        'cod_enabled' => 'boolean',
        'free_shipping_enabled' => 'boolean',
        'shipping_regular_cost' => 'integer',
        'shipping_express_cost' => 'integer',
        'shipping_sameday_cost' => 'integer',
        'free_shipping_minimum' => 'integer',
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