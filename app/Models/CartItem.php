<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CartItem
 * 
 * @property int $id
 * @property int $cart_id
 * @property int $product_id
 * @property int $quantity
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Cart $cart
 * @property Product $product
 *
 * @package App\Models
 */
class CartItem extends Model
{
    protected $table = 'cart_items';

    protected $casts = [
        'cart_id' => 'int',
        'product_id' => 'int',
        'quantity' => 'int',
        'price' => 'decimal:2',
        'subtotal' => 'decimal:2'
    ];

    protected $fillable = [
        'cart_id',
        'product_id',
        'quantity',
        'price',
        'subtotal'
    ];

    // Relationships
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Helper Methods
    public function calculateSubtotal()
    {
        $subtotal = $this->quantity * $this->price;
        $this->update(['subtotal' => $subtotal]);
        return $subtotal;
    }

    public function updateQuantity($newQuantity)
    {
        $this->quantity = $newQuantity;
        $this->calculateSubtotal();
        $this->save();
    }

    public function increaseQuantity($amount = 1)
    {
        $this->increment('quantity', $amount);
    }

    public function decreaseQuantity($amount = 1)
    {
        if ($this->quantity > $amount) {
            $this->decrement('quantity', $amount);
        } else {
            $this->delete();
        }
    }
}