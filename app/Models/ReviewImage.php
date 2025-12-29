<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ReviewImage extends Model
{
    protected $fillable = [
        'product_review_id',
        'image_path',
    ];

    // Relationships
    public function review()
    {
        return $this->belongsTo(ProductReview::class, 'product_review_id');
    }

    // Accessor
    public function getImageUrlAttribute()
    {
        return Storage::url($this->image_path);
    }

    // Delete image file when model is deleted
    protected static function booted()
    {
        static::deleting(function ($image) {
            if (Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }
        });
    }
}
