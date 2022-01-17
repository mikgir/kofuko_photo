<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileManagerService implements FileManagerServiceInterface
{
    private $galleryImageDirectory;

    public function __construct($galleryImageDirectory)
    {
        $this->galleryImageDirectory = $galleryImageDirectory;
    }

    /**
     * @return mixed
     */
    public function getGalleryImageDirectory()
    {
        return $this->galleryImageDirectory;
    }

    public function imageGalleryUpload(UploadedFile $file): string
    {
        $fileName = uniqid() . '.' . $file->guessExtension();
        try {
            $file->move($this->getGalleryImageDirectory(), $fileName);
        } catch (FileException $exception) {
            return $exception;
        }
        return $fileName;
    }

    public function removeGalleryImage(string $fileName)
    {
        // TODO: Implement removeGalleryImage() method.
    }

}