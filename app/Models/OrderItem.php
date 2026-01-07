<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class OrderItem
 * 
 * @property int $id
 * @property int $order_id
 * @property int $product_id
 * @property int $quantity
 * @property decimal $price
 * @property decimal $subtotal
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Order $order
 * @property Product $product
 *
 * @package App\Models
 */
class OrderItem extends Model
{
    protected $table = 'order_items';
    public $timestamps = false;

    protected $casts = [
        'order_id' => 'int',
        'product_id' => 'int',
        'quantity' => 'int',
        'price' => 'decimal:2',
        'subtotal' => 'decimal:2'
    ];

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'subtotal'
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function review()
    {
        return $this->hasOne(ProductReview::class);
    }

    // Helper Methods
    public function calculateSubtotal()
    {
        return $this->price * $this->quantity;
    }
}