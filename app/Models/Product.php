<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Product
 * 
 * @property int $id
 * @property int $category_id
 * @property string $name
 * @property decimal $price
 * @property string|null $description
 * @property string $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Category $category
 * @property Collection|CartItem[] $cart_items
 * @property Collection|OrderItem[] $order_items
 * @property Collection|ProductImage[] $product_images
 * @property Collection|ProductStock[] $product_stocks
 *
 * @package App\Models
 */
class Product extends Model
{
    protected $table = 'products';

    protected $casts = [
        'category_id' => 'int',
        'price' => 'decimal:2'
    ];

    protected $fillable = [
        'category_id',
        'name',
        'price',
        'description',
        'status'
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function cart_items()
    {
        return $this->hasMany(CartItem::class);
    }

    public function order_items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function product_images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function product_stocks()
    {
        return $this->hasMany(ProductStock::class);
    }

    // Helper Methods
    public function isActive()
    {
        return $this->status === 'active';
    }

    public function isInactive()
    {
        return $this->status === 'inactive';
    }

    public function isArchived()
    {
        return $this->status === 'archived';
    }

    public function getPrimaryImage()
    {
        return $this->product_images()->where('is_primary', true)->first() 
            ?? $this->product_images()->first();
    }

    public function getCurrentStock()
    {
        $stockIn = $this->product_stocks()->where('type', 'in')->sum('quantity');
        $stockOut = $this->product_stocks()->where('type', 'out')->sum('quantity');
        
        return $stockIn - $stockOut;
    }

    public function isInStock()
    {
        return $this->getCurrentStock() > 0;
    }

    public function addStock($quantity, $note = null)
    {
        return $this->product_stocks()->create([
            'quantity' => $quantity,
            'type' => 'in',
            'note' => $note
        ]);
    }

    public function reduceStock($quantity, $note = null)
    {
        if (!$this->hasEnoughStock($quantity)) {
            return false;
        }

        return $this->product_stocks()->create([
            'quantity' => $quantity,
            'type' => 'out',
            'note' => $note
        ]);
    }

    public function hasEnoughStock($requestedQuantity)
    {
        return $this->getCurrentStock() >= $requestedQuantity;
    }

    public function activate()
    {
        $this->update(['status' => 'active']);
    }

    public function deactivate()
    {
        $this->update(['status' => 'inactive']);
    }

    public function archive()
    {
        $this->update(['status' => 'archived']);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    public function scopeArchived($query)
    {
        return $query->where('status', 'archived');
    }

    public function scopeInStock($query)
    {
        return $query->whereHas('product_stocks', function ($q) {
            $q->selectRaw('product_id')
              ->selectRaw('SUM(CASE WHEN type = "in" THEN quantity ELSE 0 END) - SUM(CASE WHEN type = "out" THEN quantity ELSE 0 END) as stock')
              ->groupBy('product_id')
              ->having('stock', '>', 0);
        });
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('description', 'like', "%{$term}%");
        });
    }
}