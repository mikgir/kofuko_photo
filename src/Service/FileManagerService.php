<?php

namespace App\Service;

use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileManagerService implements FileManagerServiceInterface
{
    private $galleryImageDirectory;
    private $blogImageDirectory;
    private $avatarImageDirectory;

    public function __construct($galleryImageDirectory, $blogImageDirectory, $avatarImageDirectory)
    {
        $this->galleryImageDirectory = $galleryImageDirectory;
        $this->blogImageDirectory = $blogImageDirectory;
        $this->avatarImageDirectory = $avatarImageDirectory;
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
        $fileSystem = new Filesystem();
        $fileImage = $this->getGalleryImageDirectory() . '/' . $fileName;
        try {
            $fileSystem->remove($fileImage);
        } catch (IOExceptionInterface $exception) {
            return $exception->getMessage();
        }
        return $this;
    }

    public function getBlogImageDirectory()
    {
        return $this->blogImageDirectory;
    }

    public function imageBlogUpload(UploadedFile $file): string
    {
        $fileName = uniqid() . '.' . $file->guessExtension();
        try {
            $file->move($this->getBlogImageDirectory(), $fileName);
        } catch (FileException $exception) {
            return $exception;
        }
        return $fileName;
    }

    public function removeBlogImage(string $fileName)
    {
        $fileSystem = new Filesystem();
        $fileImage = $this->getBlogImageDirectory() . '/' . $fileName;
        try {
            $fileSystem->remove($fileImage);
        } catch (IOExceptionInterface $exception) {
            return $exception->getMessage();
        }
        return $this;
    }

    public function getAvatarImageDirectory()
    {
        return $this->avatarImageDirectory;
    }
    public function imageAvatarUpload(UploadedFile $file): string
    {
        $fileName = uniqid() . '.' . $file->guessExtension();
        try {
            $file->move($this->getAvatarImageDirectory(), $fileName);
        } catch (FileException $exception) {
            return $exception;
        }
        return $fileName;
    }
    public function removeAvatarImage(string $fileName)
    {
        $fileSystem = new Filesystem();
        $fileImage = $this->getAvatarImageDirectory() . '/' . $fileName;
        try {
            $fileSystem->remove($fileImage);
        } catch (IOExceptionInterface $exception) {
            return $exception->getMessage();
        }
        return $this;
    }
}