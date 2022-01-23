<?php

namespace App\Repository;

use App\Entity\Gallery;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Gallery|null find($id, $lockMode = null, $lockVersion = null)
 * @method Gallery|null findOneBy(array $criteria, array $orderBy = null)
 * @method Gallery[]    findAll()
 * @method Gallery[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GalleryRepository extends ServiceEntityRepository implements GalleryRepositoryInterface
{
    private $entityManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct($registry, Gallery::class);
    }

    public function getAllGalleries(): array
    {
        return parent::findAll();
    }

    public function getOneGallery(int $galleryId): object
    {
        return parent::find($galleryId);
    }

    public function setCreateGallery(Gallery $gallery): object
    {
        $gallery->setTitle();
        $this->entityManager->persist($gallery);
        $this->entityManager->flush();
        return $gallery;
    }

    public function setUpdateGallery(Gallery $gallery): object
    {
        $gallery->setTitle();
        $this->entityManager->flush();
        return $gallery;

    }

    public function setDeleteGallery(Gallery $gallery)
    {
        $this->entityManager->remove($gallery);
        $this->entityManager->flush();
    }


    // /**
    //  * @return Gallery[] Returns an array of Gallery objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Gallery
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
