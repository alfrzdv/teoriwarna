<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Complaint
 * 
 * @property int $id
 * @property int $user_id
 * @property int $order_id
 * @property string $reason
 * @property string $description
 * @property string $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Order $order
 * @property User $user
 *
 * @package App\Models
 */
class Complaint extends Model
{
	protected $table = 'complaints';

	protected $casts = [
		'user_id' => 'int',
		'order_id' => 'int'
	];

	protected $fillable = [
		'user_id',
		'order_id',
		'reason',
		'description',
		'status'
	];

	public function order()
	{
		return $this->belongsTo(Order::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
