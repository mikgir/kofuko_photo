<?php

namespace App\Repository;

use App\Entity\Image;
use App\Service\FileManagerServiceInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @method Image|null find($id, $lockMode = null, $lockVersion = null)
 * @method Image|null findOneBy(array $criteria, array $orderBy = null)
 * @method Image[]    findAll()
 * @method Image[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImageRepository extends ServiceEntityRepository implements ImageRepositoryInterface
{
    private $entityManager;
    private $managerService;

    public function __construct(ManagerRegistry             $registry,
                                EntityManagerInterface      $entityManager,
                                FileManagerServiceInterface $managerService)
    {
        $this->entityManager = $entityManager;
        $this->managerService = $managerService;
        parent::__construct($registry, Image::class);
    }

    /**
     * @return array|Image[]
     */
    public function getAllImages(): array
    {
        return parent::findAll();
    }

    /**
     * @param int $id
     * @return Image
     */
    public function getOneImage(int $id): object
    {
        return parent::find($id);
    }

    /**
     * @param Image $image
     * @param UploadedFile $file
     * @return Image
     */
    public function setCreateImage(Image $image, UploadedFile $file): object
    {
        if ($file) {
            $fileName = $this->managerService->imageGalleryUpload($file);
            $image->setFileName($fileName);
        }
        $image->setUploadedAt(date_create_immutable());
        $image->setUpdatedAt(date_create_immutable());
        $image->setIsPublished();
        $this->entityManager->persist($image);
        $this->entityManager->flush();

        return $image;
    }

    /**
     * @param Image $image
     * @param UploadedFile $file
     * @return Image
     */
    public function setUpdateImage(Image $image, UploadedFile $file): object
    {
        if ($file) {
            $fileName = $image->getFileName();
            if ($fileName) {
                $this->managerService->removeGalleryImage($fileName);
            }
            $fileName = $this->managerService->imageGalleryUpload($file);
            $image->setFileName($fileName);
        }
        $image->setUpdatedAt(date_create_immutable());
        $this->entityManager->flush();

        return $image;
    }

    /**
     * @param Image $image
     * @param $fileName
     */
    public function setDeleteImage(Image $image, $fileName)
    {
        $fileName = $image->getFileName();
        if ($fileName) {
            $this->managerService->removeGalleryImage($fileName);
        }
        $this->entityManager->remove($image);
        $this->entityManager->flush();
    }


    // /**
    //  * @return Image[] Returns an array of Image objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Image
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
