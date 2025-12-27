<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Cart
 * 
 * @property int $id
 * @property int $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User $user
 * @property Collection|CartItem[] $cart_items
 *
 * @package App\Models
 */
class Cart extends Model
{
    protected $table = 'carts';

    protected $casts = [
        'user_id' => 'int'
    ];

    protected $fillable = [
        'user_id'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cart_items()
    {
        return $this->hasMany(CartItem::class);
    }

    // Helper Methods
    public function getTotalItems()
    {
        return $this->cart_items->sum('quantity');
    }

    public function getTotal()
    {
        return $this->cart_items->sum('subtotal');
    }

    public function isEmpty()
    {
        return $this->cart_items->isEmpty();
    }

    public function hasProduct($productId)
    {
        return $this->cart_items()->where('product_id', $productId)->exists();
    }

    public function addItem($productId, $quantity = 1)
    {
        $product = \App\Models\Product::find($productId);
        
        if (!$product) {
            return false;
        }

        $cartItem = $this->cart_items()->where('product_id', $productId)->first();

        if ($cartItem) {
            $cartItem->quantity += $quantity;
            $cartItem->subtotal = $cartItem->quantity * $cartItem->price;
            $cartItem->save();
            return $cartItem;
        }

        return $this->cart_items()->create([
            'product_id' => $productId,
            'quantity' => $quantity,
            'price' => $product->price,
            'subtotal' => $quantity * $product->price
        ]);
    }

    public function removeItem($productId)
    {
        return $this->cart_items()->where('product_id', $productId)->delete();
    }

    public function updateItemQuantity($productId, $quantity)
    {
        $cartItem = $this->cart_items()->where('product_id', $productId)->first();
        
        if ($cartItem) {
            $cartItem->quantity = $quantity;
            $cartItem->subtotal = $cartItem->quantity * $cartItem->price;
            $cartItem->save();
            return true;
        }
        
        return false;
    }

    public function clear()
    {
        return $this->cart_items()->delete();
    }
}