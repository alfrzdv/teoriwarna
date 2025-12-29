# Thumbnail Feature - Usage Guide

## Overview
Automatic thumbnail generation for uploaded images (profile pictures & product images) using Intervention Image library.

## Installation

```bash
composer require intervention/image
```

## Features

### 1. Profile Picture Thumbnails
- **Size:** 150x150 pixels
- **Location:** `storage/app/public/profile-pictures/`
- **Naming:** `thumb_{original_filename}`

**Usage in Views:**
```blade
{{-- Original image --}}
<img src="{{ $user->getProfilePictureUrl() }}" alt="Profile Picture">

{{-- Thumbnail --}}
<img src="{{ $user->getProfilePictureThumbnailUrl() }}" alt="Profile Thumbnail">
```

### 2. Product Image Thumbnails
- **Size:** 400x400 pixels
- **Location:** `storage/app/public/products/`
- **Naming:** `thumb_{original_filename}`

**Usage in Views:**
```blade
{{-- Original image --}}
<img src="{{ $productImage->getUrl() }}" alt="Product">

{{-- Thumbnail --}}
<img src="{{ $productImage->getThumbnailUrl() }}" alt="Product Thumbnail">
```

## ImageHelper Methods

### Upload with Thumbnail
```php
use App\Helpers\ImageHelper;

$result = ImageHelper::uploadWithThumbnail(
    $request->file('image'),
    'directory-name',  // e.g., 'products', 'profile-pictures'
    400,              // width
    400               // height
);

// Returns: ['original' => 'path/to/image.jpg', 'thumbnail' => 'path/to/thumb_image.jpg']
$imagePath = $result['original'];
```

### Create Thumbnail from Existing Image
```php
$thumbnailPath = ImageHelper::createThumbnail(
    'products/image.jpg',  // original image path
    400,                   // width
    400                    // height
);
```

### Delete Image with Thumbnail
```php
ImageHelper::deleteWithThumbnail('products/image.jpg');
// Deletes both original and thumbnail
```

### Get Thumbnail URL
```php
$thumbnailUrl = ImageHelper::getThumbnailUrl('products/image.jpg');
// Returns URL to thumbnail or falls back to original if thumbnail doesn't exist
```

### Get Thumbnail Path
```php
$thumbnailPath = ImageHelper::getThumbnailPath('products/image.jpg');
// Returns: 'products/thumb_image.jpg'
```

## Implementation Examples

### Profile Controller (Already Implemented)
```php
// Upload profile picture with thumbnail
if ($request->hasFile('profile_picture')) {
    if ($user->profile_picture) {
        ImageHelper::deleteWithThumbnail($user->profile_picture);
    }

    $result = ImageHelper::uploadWithThumbnail(
        $request->file('profile_picture'),
        'profile-pictures',
        150,
        150
    );
    $user->profile_picture = $result['original'];
}
```

### Product Controller (Already Implemented)
```php
// Upload product images with thumbnails
if ($request->hasFile('images')) {
    foreach ($request->file('images') as $index => $image) {
        $result = ImageHelper::uploadWithThumbnail($image, 'products', 400, 400);

        ProductImage::create([
            'product_id' => $product->id,
            'image_path' => $result['original'],
            'is_primary' => $index === 0,
        ]);
    }
}
```

## Benefits

1. **Performance:** Load thumbnails in listings for faster page load
2. **Bandwidth:** Reduce data transfer for mobile users
3. **UX:** Smooth browsing experience with optimized images
4. **SEO:** Faster load times improve search rankings

## Default Sizes

| Type | Width | Height | Use Case |
|------|-------|--------|----------|
| Profile Picture | 150px | 150px | User avatars, comments |
| Product Image | 400px | 400px | Product listings, grids |

## Customization

To change thumbnail sizes, modify the dimensions in controllers:

```php
// For smaller thumbnails (e.g., 200x200)
$result = ImageHelper::uploadWithThumbnail($file, 'products', 200, 200);

// For larger thumbnails (e.g., 600x600)
$result = ImageHelper::uploadWithThumbnail($file, 'products', 600, 600);
```

## Troubleshooting

**Thumbnails not generating?**
- Check GD extension is enabled: `php -m | grep gd`
- Verify storage directory permissions: `storage/app/public/` should be writable
- Run: `php artisan storage:link`

**Thumbnails low quality?**
- Intervention Image uses GD driver by default
- For better quality, install Imagick: `pecl install imagick`

## Notes

- Thumbnails are generated with **aspect ratio preserved** using `cover()` mode
- Images are centered and cropped to fit the specified dimensions
- Original images are always preserved
- Automatic cleanup when deleting images
