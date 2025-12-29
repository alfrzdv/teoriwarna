<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageHelper
{
    /**
     * Create thumbnail from uploaded image
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $path Directory path to store (e.g., 'products', 'profile-pictures')
     * @param int $width Thumbnail width
     * @param int $height Thumbnail height
     * @param string $disk Storage disk (default: 'public')
     * @return array ['original' => 'path/to/original.jpg', 'thumbnail' => 'path/to/thumb.jpg']
     */
    public static function uploadWithThumbnail($file, $path, $width = 300, $height = 300, $disk = 'public')
    {
        // Store original image
        $originalPath = $file->store($path, $disk);

        // Try to generate thumbnail (but don't fail if it errors)
        try {
            $thumbnailPath = static::createThumbnail($originalPath, $width, $height, $disk);
        } catch (\Exception $e) {
            \Log::warning('Thumbnail generation failed during upload: ' . $e->getMessage());
            $thumbnailPath = $originalPath; // Fallback to original
        }

        return [
            'original' => $originalPath,
            'thumbnail' => $thumbnailPath
        ];
    }

    /**
     * Create thumbnail from existing image
     *
     * @param string $originalPath Path to original image
     * @param int $width Thumbnail width
     * @param int $height Thumbnail height
     * @param string $disk Storage disk
     * @return string Path to thumbnail
     */
    public static function createThumbnail($originalPath, $width = 300, $height = 300, $disk = 'public')
    {
        // Get full path to original
        $fullPath = Storage::disk($disk)->path($originalPath);

        // Create thumbnail path
        $pathInfo = pathinfo($originalPath);
        $thumbnailPath = $pathInfo['dirname'] . '/thumb_' . $pathInfo['basename'];
        $thumbnailFullPath = Storage::disk($disk)->path($thumbnailPath);

        // Create directory if not exists
        $thumbnailDir = dirname($thumbnailFullPath);
        if (!file_exists($thumbnailDir)) {
            mkdir($thumbnailDir, 0755, true);
        }

        // Check if GD functions are available
        if (!function_exists('imagecreatefromjpeg') ||
            !function_exists('imagecreatefrompng') ||
            !function_exists('imagecreatefromgif')) {
            // Fallback: copy original as thumbnail
            \Log::info('GD functions not available, using original image as thumbnail');
            copy($fullPath, $thumbnailFullPath);
            return $thumbnailPath;
        }

        // Create ImageManager with GD driver
        $manager = new ImageManager(new Driver());

        // Read image and create thumbnail
        $image = $manager->read($fullPath);

        // Resize with aspect ratio (cover mode)
        $image->cover($width, $height);

        // Save thumbnail
        $image->save($thumbnailFullPath);

        return $thumbnailPath;
    }

    /**
     * Delete image and its thumbnail
     *
     * @param string $imagePath Path to original image
     * @param string $disk Storage disk
     * @return bool
     */
    public static function deleteWithThumbnail($imagePath, $disk = 'public')
    {
        if (!$imagePath) {
            return false;
        }

        // Delete original
        if (Storage::disk($disk)->exists($imagePath)) {
            Storage::disk($disk)->delete($imagePath);
        }

        // Delete thumbnail
        $pathInfo = pathinfo($imagePath);
        $thumbnailPath = $pathInfo['dirname'] . '/thumb_' . $pathInfo['basename'];

        if (Storage::disk($disk)->exists($thumbnailPath)) {
            Storage::disk($disk)->delete($thumbnailPath);
        }

        return true;
    }

    /**
     * Get thumbnail path from original image path
     *
     * @param string $imagePath Original image path
     * @return string Thumbnail path
     */
    public static function getThumbnailPath($imagePath)
    {
        if (!$imagePath) {
            return null;
        }

        $pathInfo = pathinfo($imagePath);
        return $pathInfo['dirname'] . '/thumb_' . $pathInfo['basename'];
    }

    /**
     * Get thumbnail URL from original image path
     *
     * @param string $imagePath Original image path
     * @param string $disk Storage disk
     * @return string|null Thumbnail URL or null
     */
    public static function getThumbnailUrl($imagePath, $disk = 'public')
    {
        if (!$imagePath) {
            return null;
        }

        $thumbnailPath = static::getThumbnailPath($imagePath);

        if (Storage::disk($disk)->exists($thumbnailPath)) {
            return Storage::disk($disk)->url($thumbnailPath);
        }

        // Fallback to original
        return Storage::disk($disk)->url($imagePath);
    }
}
