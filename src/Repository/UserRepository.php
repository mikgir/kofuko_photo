<?php

namespace App\Repository;

use App\Entity\User;
use App\Service\FileManagerServiceInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface, UserRepositoryInterface
{
    private $entityManager;
    private $serviceManager;

    public function __construct(ManagerRegistry             $registry,
                                EntityManagerInterface      $entityManager,
                                FileManagerServiceInterface $serviceManager)
    {
        $this->entityManager = $entityManager;
        $this->serviceManager = $serviceManager;
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function getAllUsers(): array
    {
        return parent::findAll();
    }

    /**
     * @param $id
     * @return User
     */
    public function getOneUser($id): object
    {
        return parent::find($id);
    }

    /**
     * @param User $user
     * @param UploadedFile $file
     * @return User
     */
    public function setCreateUser(User $user, UploadedFile $file): object
    {
        if ($file) {
            $fileName = $this->serviceManager->imageAvatarUpload($file);
            $user->setAvatarImg($fileName);
        }
        $user->setRoles(['ROLE_USER']);
        $user->setRegisteredAt(date_create_immutable());
        $user->setUpdatedAt(date_create_immutable());
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return $user;
    }

    /**
     * @param User $user
     * @param UploadedFile $file
     * @return User
     */
    public function setUpdateUser(User $user, UploadedFile $file): object
    {
        if ($file) {
            $fileName = $user->getAvatarImg();
            if ($fileName) {
                $this->serviceManager->removeAvatarImage($fileName);
            }
            $fileName = $this->serviceManager->imageAvatarUpload($file);
            $user->setAvatarImg($fileName);
        }
        $user->setUpdatedAt(date_create_immutable());
        $this->entityManager->flush();
        return $user;
    }

    /**
     * @param User $user
     * @param $fileName
     */
    public function setDeleteUser(User $user, $fileName)
    {
        $fileName = $user->getAvatarImg();
        if ($fileName) {
            $this->serviceManager->removeAvatarImage($fileName);
        }
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }


    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
