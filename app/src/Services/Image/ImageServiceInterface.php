<?php

namespace App\Services\Image;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface ImageServiceInterface
{
    public function uploadImage(UploadedFile $outfit): string;
    public function convertImageToBase64(string $filePath): string;
}
