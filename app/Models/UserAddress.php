<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserAddress
 * 
 * @property int $id
 * @property int $user_id
 * @property string $recipient_name
 * @property string $phone
 * @property string $address
 * @property string $city
 * @property string $province
 * @property string $postal_code
 * @property bool $is_default
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User $user
 * @property Collection|Order[] $orders
 *
 * @package App\Models
 */
class UserAddress extends Model
{
	protected $table = 'user_addresses';

	protected $casts = [
		'user_id' => 'int',
		'is_default' => 'bool'
	];

	protected $fillable = [
		'user_id',
		'recipient_name',
		'phone',
		'address',
		'city',
		'province',
		'postal_code',
		'is_default'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function orders()
	{
		return $this->hasMany(Order::class, 'address_id');
	}
}
