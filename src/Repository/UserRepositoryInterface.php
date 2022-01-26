<?php

namespace App\Repository;

use App\Entity\User;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface UserRepositoryInterface
{
    /**
     * @return User[]
     */
    public function getAllUsers(): array;

    /**
     * @param $id
     * @return User
     */
    public function getOneUser($id): object;

    /**
     * @param User $user
     * @param UploadedFile $file
     * @return User
     */
    public function setCreateUser(User $user, UploadedFile $file): object;

    /**
     * @param User $user
     * @param UploadedFile $file
     * @return User
     */
    public function setUpdateUser(User $user, UploadedFile $file): object;

    /**
     * @param User $user
     * @param $fileName
     */
    public function setDeleteUser(User $user, $fileName);

}