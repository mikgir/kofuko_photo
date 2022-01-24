<?php

namespace App\Repository;

use App\Entity\Image;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface ImageRepositoryInterface
{
    /**
     * @return Image[]
     */
    public function getAllImages(): array;

    /**
     * @param int $id
     * @return Image
     */
    public function getOneImage(int $id): object;

    /**
     * @param Image $image
     * @param UploadedFile $file
     * @return Image
     */
    public function setCreateImage(Image $image, UploadedFile $file): object;

    /**
     * @param Image $image
     * @param UploadedFile $file
     * @return Image
     */
    public function setUpdateImage(Image $image, UploadedFile $file): object;

    /**
     * @param Image $image
     * @param $fileName
     */
    public function setDeleteImage(Image $image, $fileName);

}