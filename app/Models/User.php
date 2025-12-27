<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class User
 * 
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string|null $phone
 * @property string $role
 * @property bool $is_active
 * @property Carbon|null $last_login
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|AdminLog[] $admin_logs
 * @property Collection|Cart[] $carts
 * @property Collection|Complaint[] $complaints
 * @property Collection|Notification[] $notifications
 * @property Collection|Order[] $orders
 * @property Collection|UserAddress[] $user_addresses
 * @property Collection|UserSetting[] $user_settings
 *
 * @package App\Models
 */
class User extends Model
{
	protected $table = 'users';

	protected $casts = [
		'is_active' => 'bool',
		'last_login' => 'datetime'
	];

	protected $hidden = [
		'password'
	];

	protected $fillable = [
		'name',
		'email',
		'password',
		'phone',
		'role',
		'is_active',
		'last_login'
	];

	public function admin_logs()
	{
		return $this->hasMany(AdminLog::class, 'admin_id');
	}

	public function carts()
	{
		return $this->hasMany(Cart::class);
	}

	public function complaints()
	{
		return $this->hasMany(Complaint::class);
	}

	public function notifications()
	{
		return $this->hasMany(Notification::class);
	}

	public function orders()
	{
		return $this->hasMany(Order::class);
	}

	public function user_addresses()
	{
		return $this->hasMany(UserAddress::class);
	}

	public function user_settings()
	{
		return $this->hasMany(UserSetting::class);
	}
}
