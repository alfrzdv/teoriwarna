<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Category
 * 
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Product[] $products
 *
 * @package App\Models
 */
class Category extends Model
{
    protected $table = 'categories';

    protected $casts = [
        'is_active' => 'bool'
    ];

    protected $fillable = [
        'name',
        'description',
        'is_active',
        'background_color',
        'text_color',
        'style_type'
    ];

    // Relationships
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Helper Methods
    public function getActiveProductsCount()
    {
        return $this->products()->active()->count();
    }

    public function hasProducts()
    {
        return $this->products()->exists();
    }

    public function activate()
    {
        $this->update(['is_active' => true]);
    }

    public function deactivate()
    {
        $this->update(['is_active' => false]);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeWithActiveProducts($query)
    {
        return $query->whereHas('products', function ($q) {
            $q->active();
        });
    }
}