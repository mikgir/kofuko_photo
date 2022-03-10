<?php

namespace App\Controller\Admin;

use App\Repository\UserRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/admin/user", name="admin_user")
     */
    public function index(): Response
    {
        $users=$this->userRepository->getAllUsers();
        $forRender = [
            'title' => 'Список пользователей',
            'users'=>$users
        ];
        return $this->render('admin/user/index.html.twig', $forRender);
    }
}
