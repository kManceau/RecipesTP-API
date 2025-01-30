<?php

namespace App\Services;

class ImageService
{
    public function createImages($image, $folder):\GdImage
    {
        $image = imagecreatefromstring(file_get_contents($image));
        return $this->resizeImage($image, $folder);
    }

    public function resizeImage($image, $folder){
        $originalWidth = ImageSX($image);
        $originalHeight = ImageSY($image);
        $maxWidth = match ($folder) {
            'recipes', 'ingredients' => 2000,
            default => $originalWidth,
        };
        if ($originalWidth > $maxWidth) {
            $ratio = $maxWidth / $originalWidth;
            $maxHeight = $originalHeight * $ratio;
        } else {
            $maxWidth = $originalWidth;
            $maxHeight = $originalHeight;
        }
        imagepalettetotruecolor($image);
        $resizedImage = imagecreatetruecolor($maxWidth, $maxHeight);
        imagealphablending($resizedImage, false);
        imagesavealpha($resizedImage, true);
        imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, $maxWidth, $maxHeight, $originalWidth, $originalHeight);
        imagedestroy($image);
        return $resizedImage;
    }

    public function uploadImages($img, $name, $folder):void
    {
//        dd($img);
        $image = $this->createImages($img, $folder);
        $path = storage_path("app/public/$folder/$name");
        imagejpeg($image, $path.'.jpg', 100);
        imagewebp($image, $path.'.webp', 100);
        imageavif($image, $path.'.avif', 80);
        imagedestroy($image);
    }

    public function deleteImages($name, $folder):void
    {
        $path = storage_path("app/public/$folder/$name");
        if(file_exists($path.'.avif')){
            unlink($path.'.avif');
        }
        if(file_exists($path.'.jpg')){
            unlink($path.'.jpg');
        }
        if(file_exists($path.'.webp')){
            unlink($path.'.webp');
        }
    }
}

