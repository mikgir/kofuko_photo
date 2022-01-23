<?php

namespace App\Repository;

use App\Entity\Blog;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface BlogRepositoryInterface
{
    /**
     * @return Blog[]
     */
    public function getAllBlogs(): array;

    /**
     * @param int $id
     * @return Blog
     */
    public function getOneBlog(int $id): object;

    /**
     * @param Blog $blog
     * @param UploadedFile $file
     * @return Blog
     */
    public function setCreateBlog(Blog $blog, UploadedFile $file): object;

    /**
     * @param Blog $blog
     * @param UploadedFile $file
     * @return Blog
     */
    public function setUpdateBlog(Blog $blog, UploadedFile $file): object;

    /**
     * @param Blog $blog
     * @param $fileName
     */
    public function setDeleteBlog(Blog $blog, $fileName);

}