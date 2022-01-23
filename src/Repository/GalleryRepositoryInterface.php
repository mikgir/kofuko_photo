<?php

namespace App\Repository;

use App\Entity\Gallery;

interface GalleryRepositoryInterface
{
    /**
     * @return Gallery[]
     */
    public function getAllGalleries(): array;

    /**
     * @param int $galleryId
     * @return Gallery
     */
    public function getOneGallery(int $galleryId): object;

    /**
     * @param Gallery $gallery
     * @return Gallery
     */
    public function setCreateGallery(Gallery $gallery): object;

    /**
     * @param Gallery $gallery
     * @return Gallery
     */
    public function setUpdateGallery(Gallery $gallery): object;

    /**
     * @param Gallery $gallery
     */
    public function setDeleteGallery(Gallery $gallery);
}