<?php

namespace App\Controller\Admin;

use App\Entity\Gallery;
use App\Entity\Image;
use App\Form\ImageType;
use App\Service\FileManagerServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ImageController extends AbstractController
{
    /**
     * @Route("/admin/image", name="admin_image")
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $galleryManager = $entityManager->getRepository(Gallery::class);
        $imageManager = $entityManager->getRepository(Image::class);
        $galleries = $galleryManager->findAll();
        $images = $imageManager->findAll();

        $forRender = [
            'title' => 'Галереи',
            'galleries' => $galleries,
            'images' => $images
        ];
        return $this->render('admin/image/index.html.twig', $forRender);
    }

    /**
     * @Route ("/admin/image/create", name="admin_image_create")
     * @param Request $request
     * @param FileManagerServiceInterface $fileManagerService
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function createAction(Request $request,
                                 FileManagerServiceInterface $fileManagerService,
                                EntityManagerInterface $entityManager): Response
    {
        $image = new Image();
        $imageForm = $this->createForm(ImageType::class);
        $imageForm->handleRequest($request);
        if ($imageForm->isSubmitted() && $imageForm->isValid()) {
            $imageFile = $imageForm->get('file_name')->getData();
            if ($imageFile) {
                $fileName = $fileManagerService->imageGalleryUpload($imageFile);
                $image->setFileName($fileName);
            }
            $image->setGallery($imageForm->get('gallery')->getData());
            $image->setTitle($imageForm->get('title')->getData());
            $entityManager->persist($image);
            $entityManager->flush();
            $this->addFlash('success', 'Фотография добавлена');
            return $this->redirectToRoute('admin_image');
        }

        $forRender=[
            'title'=>'Создание галерей',
            'image_form'=>$imageForm->createView()
        ];
        return $this->render('admin/image/form_image.html.twig', $forRender);
    }
}
