<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UserSetting
 * 
 * @property int $id
 * @property int $user_id
 * @property bool $notification_enabled
 * @property bool $promo_enabled
 * 
 * @property User $user
 *
 * @package App\Models
 */
class UserSetting extends Model
{
	protected $table = 'user_settings';
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int',
		'notification_enabled' => 'bool',
		'promo_enabled' => 'bool'
	];

	protected $fillable = [
		'user_id',
		'notification_enabled',
		'promo_enabled'
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
