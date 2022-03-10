<?php

namespace App\Controller\Admin;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $users = $entityManager->getRepository(User::class)->findAll();
        $tasks = $entityManager->getRepository(Task::class)->findAll();

        $forRender = [
            'title' => 'admin page',
            'users' => $users,
            'tasks' => $tasks
        ];
        return $this->render('admin/index_admin.html.twig', $forRender);
    }
}
