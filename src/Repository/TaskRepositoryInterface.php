<?php

namespace App\Repository;

use App\Entity\Task;
use Symfony\Component\HttpFoundation\Request;

interface TaskRepositoryInterface
{
    /**
     * @return array
     */
    public function getAllTask(): array;

    /**
     * @param int $id
     * @return object
     */
    public function getOneTask(int $id): object;

    /**
     * @param Task $task
     * @param $taskForm
     * @return object
     */

    public function setCreateTask(Task $task, $taskForm): object;

    /**
     * @param Task $task
     * @param $taskForm
     * @return object
     */
    public function setUpdateTask(Task $task, $taskForm): object;

    /**
     * @param Task $task
     * @return mixed
     */
    public function setDeleteTask(Task $task);

}