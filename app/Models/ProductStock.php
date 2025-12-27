<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ProductStock
 * 
 * Stock Movement / Transaction Model
 * This tracks IN and OUT stock movements, not current stock levels
 * 
 * @property int $id
 * @property int $product_id
 * @property int $quantity
 * @property string $type (in/out)
 * @property string|null $note
 * @property Carbon|null $created_at
 * 
 * @property Product $product
 *
 * @package App\Models
 */
class ProductStock extends Model
{
    protected $table = 'product_stocks';
    
    public $timestamps = false;
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    protected $casts = [
        'product_id' => 'int',
        'quantity' => 'int',
        'created_at' => 'datetime'
    ];

    protected $fillable = [
        'product_id',
        'quantity',
        'type',
        'note'
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Helper Methods
    public function isStockIn()
    {
        return $this->type === 'in';
    }

    public function isStockOut()
    {
        return $this->type === 'out';
    }

    // Scopes
    public function scopeStockIn($query)
    {
        return $query->where('type', 'in');
    }

    public function scopeStockOut($query)
    {
        return $query->where('type', 'out');
    }

    public function scopeByProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}