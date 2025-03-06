<?php

namespace App\Services\Image;


use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\Exception\FileException;



class ImageService implements ImageServiceInterface
{
    private SluggerInterface $slugger;
    private string $targetDirectory;
    private LoggerInterface $logger;

    public function __construct(
        SluggerInterface $slugger,
        #[Autowire('%kernel.project_dir%/public')] string $targetDirectory,
        LoggerInterface $logger
    ) {
        $this->slugger = $slugger;
        $this->targetDirectory = rtrim($targetDirectory, '/');
        $this->logger = $logger;
    }

    public function uploadImage(UploadedFile $file, $folder = '/uploads/images/'): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = strtolower($this->slugger->slug($originalFilename));
        $fileName = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        try {
            $file->move($this->targetDirectory . $folder, $fileName);
        } catch (FileException $e) {
            $this->logger->error('An error occurred while uploading the file: ' . $e->getMessage());
        }

        return $fileName;
    }

    public function convertImageToBase64(string $filePath): string
    {
        $imagePath = $this->targetDirectory . '/uploads/images/' . $filePath;
        $imageData = file_get_contents($imagePath);
        $base64Image = base64_encode($imageData);
        return $base64Image;
    }
}
