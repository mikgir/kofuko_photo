<?php

namespace App\Controller\Admin;

use App\Entity\Image;
use App\Form\ImageType;
use App\Repository\GalleryRepositoryInterface;
use App\Repository\ImageRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ImageController extends AbstractController
{
    private $galleryRepository;
    private $imageRepository;

    public function __construct(GalleryRepositoryInterface $galleryRepository, ImageRepositoryInterface $imageRepository)
    {
        $this->galleryRepository = $galleryRepository;
        $this->imageRepository = $imageRepository;

    }

    /**
     * @Route("/admin/image", name="admin_image")
     * @return Response
     */
    public function index(): Response
    {
        $forRender = [
            'title' => 'Галереи',
            'galleries' => $this->galleryRepository->getAllGalleries(),
            'images' => $this->imageRepository->getAllImages()
        ];
        return $this->render('admin/image/index.html.twig', $forRender);
    }

    /**
     * @Route ("/admin/image/create", name="admin_image_create")
     * @param Request $request
     * @return Response
     */
    public function createAction(Request $request): Response
    {
        $image = new Image();
        $imageForm = $this->createForm(ImageType::class);
        $imageForm->handleRequest($request);
        if ($imageForm->isSubmitted() && $imageForm->isValid()) {
            $file=$imageForm->get('file_name')->getData();
            $image->setGallery($imageForm->get('gallery')->getData());
            $this->imageRepository->setCreateImage($image, $file);
            $this->addFlash('success', 'Фотография добавлена');
            return $this->redirectToRoute('admin_image');
        }

        $forRender = [
            'title' => 'Создание галерей',
            'image_form' => $imageForm->createView()
        ];
        return $this->render('admin/image/form_image.html.twig', $forRender);
    }

    /**
     * @Route ("/admin/image/update/{id}", name="admin_image_update")
     * @param int $id
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function updateAction(int $id, Request $request)
    {
        $image = $this->imageRepository->getOneImage($id);
        $imageForm = $this->createForm(ImageType::class, $image);
        $imageForm->handleRequest($request);
        if ($imageForm->isSubmitted() && $imageForm->isValid()) {
            $fileNew=$imageForm->get('file_name')->getData();
            $image->setGallery($imageForm->get('gallery')->getData());
            $this->imageRepository->setUpdateImage($image,$fileNew);
            $this->addFlash('success', 'Фотография изменена успешно');
            return $this->redirectToRoute('admin_image');
        }

        $forRender = [
            'title' => 'Создание галерей',
            'image_form' => $imageForm->createView()
        ];
        return $this->render('admin/image/form_image.html.twig', $forRender);
    }

    /**
     * @Route ("/admin/image/delete/{id}", name="admin_image_delete")
     * @param int $id
     * @return RedirectResponse
     */
    public function removeAction(int $id): RedirectResponse
    {
        $image = $this->imageRepository->getOneImage($id);
        $fileName=$image->getFileName();
        $this->imageRepository->setDeleteImage($image, $fileName);
        $this->addFlash('success', 'Фотография удалена');
        return $this->redirectToRoute('admin_image');
    }

}
