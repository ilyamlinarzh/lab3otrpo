<?php

namespace App\Services;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\File;
use Illuminate\Http\UploadedFile;

class ImageService
{
    private ImageManager $imageManager;

    public function __construct()
    {
        $this->imageManager = new ImageManager(new Driver());
    }

    public function saveSquareImage(UploadedFile $image, string $folder = 'images'): string
    {
        $imageName = uniqid() . '_' . time() . '.webp';
        
        $directory = storage_path($folder);
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }
        
        $imagePath = $directory . '/' . $imageName;
        
        $this->imageManager
            ->read($image->getRealPath())
            ->cover(512, 512)
            ->toWebp(85)
            ->save($imagePath);
        
        return $imageName;
    }
}