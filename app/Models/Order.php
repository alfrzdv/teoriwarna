<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Order
 * 
 * @property int $id
 * @property int $user_id
 * @property int $address_id
 * @property string $order_code
 * @property float $total_price
 * @property string $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property UserAddress $user_address
 * @property User $user
 * @property Collection|Complaint[] $complaints
 * @property Collection|OrderItem[] $order_items
 * @property Collection|Payment[] $payments
 *
 * @package App\Models
 */
class Order extends Model
{
	protected $table = 'orders';

	protected $casts = [
		'user_id' => 'int',
		'address_id' => 'int',
		'total_price' => 'float'
	];

	protected $fillable = [
		'user_id',
		'address_id',
		'order_code',
		'total_price',
		'status'
	];

	public function user_address()
	{
		return $this->belongsTo(UserAddress::class, 'address_id');
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function complaints()
	{
		return $this->hasMany(Complaint::class);
	}

	public function order_items()
	{
		return $this->hasMany(OrderItem::class);
	}

	public function payments()
	{
		return $this->hasMany(Payment::class);
	}
}
