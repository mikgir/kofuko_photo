<?php

namespace App\Repository;

use App\Entity\Task;
use App\Form\TaskType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends ServiceEntityRepository implements TaskRepositoryInterface
{
    private $entityManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct($registry, Task::class);
    }

    /**
     * @return array
     */
    public function getAllTask(): array
    {
        return parent::findAll();
    }

    /**
     * @param int $id
     * @return object
     */
    public function getOneTask(int $id): object
    {
        return parent::find($id);
    }

    /**
     * @param Task $task
     * @param $taskForm
     * @return object
     */
    public function setCreateTask(Task $task, $taskForm): object
    {
        $task->setTitle($taskForm->get('title')->getData());
        $task->setDescription($taskForm->get('description')->getData());
        $task->setDueDate($taskForm->get('dueDate')->getData());
        $task->setUpdatedAt(date_create_immutable('now'));
        $task->setCreatedAt(date_create_immutable('now'));
        $this->entityManager->persist($task);
        $this->entityManager->flush();

        return $task;
    }

    /**
     * @param Task $task
     * @param $taskForm
     * @return object
     */
    public function setUpdateTask(Task $task, $taskForm): object
    {
        $task->setTitle($taskForm->get('title')->getData());
        $task->setDescription($taskForm->get('description')->getData());
        $task->setDueDate($taskForm->get('dueDate')->getData());
        $task->setUpdatedAt(date_create_immutable('now'));
        $this->entityManager->flush();

        return $task;
    }

    /**
     * @param Task $task
     * @return mixed|void
     */
    public function setDeleteTask(Task $task): void
    {
        $this->entityManager->remove($task);
        $this->entityManager->flush();
    }

    /**
     * @return array
     */
    public function getSomeTask(): array
    {
        return parent::findBy(['id'], ['due_date'], ['ASC'], ['5']);
    }


    // /**
    //  * @return Task[] Returns an array of Task objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Task
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
