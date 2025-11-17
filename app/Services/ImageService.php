<?php

namespace App\Services;

use App\Exceptions\ImageProcessingException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ImageService
{
    private ImageManager $imageManager;

    public function __construct()
    {
        $this->imageManager = new ImageManager(new Driver);
    }

    /**
     * Resize and store an uploaded image file.
     *
     * @param  UploadedFile  $file  The uploaded file
     * @param  string  $path  The storage path (e.g., 'photos/students')
     * @param  int  $maxWidth  Maximum width in pixels (default: 1200)
     * @param  int  $maxHeight  Maximum height in pixels (default: 1200)
     * @param  int  $quality  JPEG quality (default: 90)
     * @return string The stored file path
     *
     * @throws ImageProcessingException
     */
    public function resizeAndStore(
        UploadedFile $file,
        string $path,
        int $maxWidth = 1200,
        int $maxHeight = 1200,
        int $quality = 90
    ): string {
        try {
            // Create image from uploaded file
            $image = $this->imageManager->read($file->getRealPath());

            // Resize maintaining aspect ratio
            $image->scaleDown($maxWidth, $maxHeight);

            // Convert to JPEG and encode with quality
            $encoded = $image->toJpeg($quality);

            // Generate unique filename
            $filename = uniqid('', true).'.jpg';
            $fullPath = $path.'/'.$filename;

            // Store the image
            Storage::disk('public')->put($fullPath, $encoded);

            return $fullPath;
        } catch (\Exception $e) {
            throw new ImageProcessingException(
                'Failed to process image: '.$e->getMessage(),
                0,
                $e
            );
        }
    }

    /**
     * Delete an image file from storage.
     *
     * @param  string  $path  The file path to delete
     * @return bool True if deleted, false otherwise
     */
    public function delete(string $path): bool
    {
        if (empty($path)) {
            return false;
        }

        try {
            return Storage::disk('public')->delete($path);
        } catch (\Exception $e) {
            return false;
        }
    }
}
