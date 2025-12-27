<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class StoreSetting
 * 
 * @property int $id
 * @property string $store_name
 * @property string $address
 * @property string $email
 * @property string $phone
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class StoreSetting extends Model
{
	protected $table = 'store_settings';

	protected $fillable = [
		'store_name',
		'address',
		'email',
		'phone'
	];
}
