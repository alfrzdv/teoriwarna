<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CartItem
 * 
 * @property int $id
 * @property int $cart_id
 * @property int $product_id
 * @property int $quantity
 * @property float $price
 * @property float $subtotal
 * 
 * @property Cart $cart
 * @property Product $product
 *
 * @package App\Models
 */
class CartItem extends Model
{
	protected $table = 'cart_items';
	public $timestamps = false;

	protected $casts = [
		'cart_id' => 'int',
		'product_id' => 'int',
		'quantity' => 'int',
		'price' => 'float',
		'subtotal' => 'float'
	];

	protected $fillable = [
		'cart_id',
		'product_id',
		'quantity',
		'price',
		'subtotal'
	];

	public function cart()
	{
		return $this->belongsTo(Cart::class);
	}

	public function product()
	{
		return $this->belongsTo(Product::class);
	}
}
