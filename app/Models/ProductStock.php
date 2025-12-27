<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ProductStock
 * 
 * @property int $id
 * @property int $product_id
 * @property int $quantity
 * @property string $type
 * @property string|null $note
 * @property Carbon $created_at
 * 
 * @property Product $product
 *
 * @package App\Models
 */
class ProductStock extends Model
{
	protected $table = 'product_stocks';
	public $timestamps = false;

	protected $casts = [
		'product_id' => 'int',
		'quantity' => 'int'
	];

	protected $fillable = [
		'product_id',
		'quantity',
		'type',
		'note'
	];

	public function product()
	{
		return $this->belongsTo(Product::class);
	}
}
