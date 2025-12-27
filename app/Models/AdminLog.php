<?php

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
 * @property User $admin
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

    // Relationships
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    // Helper Methods
    public static function log($action, $description = null)
    {
        return self::create([
            'admin_id' => auth()->id(),
            'action' => $action,
            'description' => $description
        ]);
    }

    // Scopes
    public function scopeByAdmin($query, $adminId)
    {
        return $query->where('admin_id', $adminId);
    }

    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('created_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year);
    }
}