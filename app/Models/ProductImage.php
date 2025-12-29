<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * Class ProductImage
 *
 * @property int $id
 * @property int $product_id
 * @property string $image_path
 * @property bool $is_primary
 * @property Carbon|null $created_at
 *
 * @property Product $product
 *
 * @package App\Models
 */
class ProductImage extends Model
{
    protected $table = 'product_images';
    
    public $timestamps = false;
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    protected $casts = [
        'product_id' => 'int',
        'is_primary' => 'bool',
        'created_at' => 'datetime'
    ];

    protected $fillable = [
        'product_id',
        'image_path',
        'is_primary'
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Helper Methods
    public function getUrl()
    {
        // If it's a full URL, return as is
        if (filter_var($this->image_path, FILTER_VALIDATE_URL)) {
            return $this->image_path;
        }

        // Otherwise, return storage URL
        return Storage::url($this->image_path);
    }

    public function getThumbnailUrl()
    {
        return \App\Helpers\ImageHelper::getThumbnailUrl($this->image_path);
    }

    public function getFullPath()
    {
        return storage_path('app/public/' . $this->image_path);
    }

    public function deleteFile()
    {
        if (Storage::exists($this->image_path)) {
            Storage::delete($this->image_path);
        }
    }

    public function makePrimary()
    {
        // Set all other images as non-primary
        ProductImage::where('product_id', $this->product_id)
            ->where('id', '!=', $this->id)
            ->update(['is_primary' => false]);

        // Set this image as primary
        $this->update(['is_primary' => true]);
    }

    // Scopes
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    // Events
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($image) {
            $image->deleteFile();
        });
    }
}