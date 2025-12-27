<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AdminLog
 * 
 * @property int $id
 * @property int $admin_id
 * @property string $action
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User $user
 *
 * @package App\Models
 */
class AdminLog extends Model
{
	protected $table = 'admin_logs';

	protected $casts = [
		'admin_id' => 'int'
	];

	protected $fillable = [
		'admin_id',
		'action',
		'description'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'admin_id');
	}
}
