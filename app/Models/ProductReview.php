<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    protected $fillable = [
        'product_id',
        'user_id',
        'order_item_id',
        'rating',
        'comment',
    ];

    protected $casts = [
        'rating' => 'int',
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function images()
    {
        return $this->hasMany(ReviewImage::class);
    }

    // Scopes
    public function scopeWithRating($query, $rating)
    {
        return $query->where('rating', $rating);
    }
}
