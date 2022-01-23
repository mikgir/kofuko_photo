<?php

namespace App\Controller\Admin;

use App\Entity\Gallery;
use App\Form\GalleryType;
use App\Repository\GalleryRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GalleryController extends AbstractController
{
    private $galleryRepository;

    public function __construct(GalleryRepositoryInterface $galleryRepository)
    {
        $this->galleryRepository = $galleryRepository;
    }

    /**
     * @Route("/admin/gallery", name="admin_gallery")
     */
    public function index(): Response
    {
        $forRender = [
            'title' => 'Галереи',
            'galleries' => $this->galleryRepository->getAllGalleries()
        ];
        return $this->render('admin/gallery/index.html.twig', $forRender);
    }

    /**
     * @Route ("/admin/gallery/create", name="admin_gallery_create")
     * @param Request $request
     * @return Response
     */
    public function createAction(Request $request): Response
    {
        $gallery = new Gallery();
        $galleryForm = $this->createForm(GalleryType::class);
        $galleryForm->handleRequest($request);
        if ($galleryForm->isSubmitted() && $galleryForm->isValid()) {
            $this->galleryRepository->setCreateGallery($gallery);
            $this->addFlash('success', 'Галерея добавлена');
            return $this->redirectToRoute('admin_image');
        }

        $forRender = [
            'title' => 'Создание галерей',
            'gallery_form' => $galleryForm->createView()
        ];
        return $this->render('admin/gallery/form_gallery.html.twig', $forRender);
    }

    /**
     * @Route ("/admin/gallery/update/{id}", name="admin_gallery_update")
     * @param int $id
     * @param Request $request
     * @return Response
     */
    public function updateAction(int $id, Request $request): Response
    {
        $gallery = $this->galleryRepository->getOneGallery($id);
        $galleryForm = $this->createForm(GalleryType::class, $gallery);
        $galleryForm->handleRequest($request);
        if ($galleryForm->isSubmitted() && $galleryForm->isValid()) {
            $this->galleryRepository->setUpdateGallery($gallery);
            $this->addFlash('success', 'Изменение галереи успешно');
            return $this->redirectToRoute('admin_image');
        }

        $forRender = [
            'title' => 'Обновление галерей',
            'gallery_form' => $galleryForm->createView()
        ];
        return $this->render('admin/gallery/form_gallery.html.twig', $forRender);
    }

    /**
     * @Route ("/admin/gallery/delete/{id}", name="admin_gallery_delete")
     * @param int $id
     * @return Response
     */
    public function deleteAction(int $id): Response
    {
        $gallery=$this->galleryRepository->getOneGallery($id);
        $this->galleryRepository->setDeleteGallery($gallery);
        $this->addFlash('success', 'Галерея удалена');
        return $this->redirectToRoute('admin_image');
    }
}
