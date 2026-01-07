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
    public function productReview()
    {
        return $this->belongsTo(ProductReview::class);
    }

    // Auto delete image file when model is deleted
    protected static function booted()
    {
        static::deleting(function ($image) {
            if ($image->image_path && Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }
        });
    }
}
