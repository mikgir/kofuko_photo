<?php

namespace App\Controller\Home;

use App\Entity\Image;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $images=$entityManager->getRepository(Image::class)->findAll();
        $forRender=[
            'title'=>'kofuko photo',
            'images'=>$images
        ];
        return $this->render('home/index_home.html.twig', $forRender);
    }
}
