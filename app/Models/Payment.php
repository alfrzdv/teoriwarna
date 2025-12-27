<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Payment
 * 
 * @property int $id
 * @property int $order_id
 * @property string $method
 * @property string $status
 * @property Carbon|null $payment_date
 * @property string|null $proof
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Order $order
 *
 * @package App\Models
 */
class Payment extends Model
{
	protected $table = 'payments';

	protected $casts = [
		'order_id' => 'int',
		'payment_date' => 'datetime'
	];

	protected $fillable = [
		'order_id',
		'method',
		'status',
		'payment_date',
		'proof'
	];

	public function order()
	{
		return $this->belongsTo(Order::class);
	}
}
