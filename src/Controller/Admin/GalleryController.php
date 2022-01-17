<?php

namespace App\Controller\Admin;

use App\Entity\Gallery;
use App\Form\GalleryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GalleryController extends AbstractController
{
    /**
     * @Route("/admin/gallery", name="admin_gallery")
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $galleries = $entityManager->getRepository(Gallery::class)->findAll();
        $forRender = [
            'title' => 'Галереи',
            'galleries' => $galleries
        ];
        return $this->render('admin/gallery/index.html.twig', $forRender);
    }

    /**
     * @Route ("/admin/gallery/create", name="admin_gallery_create")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function createAction(Request $request, EntityManagerInterface $entityManager): Response
    {
        $gallery = new Gallery();
        $galleryForm = $this->createForm(GalleryType::class);
        $galleryForm->handleRequest($request);
        if ($galleryForm->isSubmitted() && $galleryForm->isValid()) {
            $gallery->setTitle($galleryForm->get('title')->getData());
            $entityManager->persist($gallery);
            $entityManager->flush();
            $this->addFlash('success', 'Галерея добавлена');
            return $this->redirectToRoute('admin_image');
        }

        $forRender=[
            'title'=>'Создание галерей',
            'gallery_form'=>$galleryForm->createView()
        ];
        return $this->render('admin/gallery/form_gallery.html.twig', $forRender);
    }
}
