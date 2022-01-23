<?php

namespace App\Controller\Admin;

use App\Entity\Blog;
use App\Form\BlogType;
use App\Repository\BlogRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    private $blogRepository;

    public function __construct(BlogRepositoryInterface $blogRepository)
    {
        $this->blogRepository = $blogRepository;
    }

    /**
     * @Route (path="/admin/blog", name="admin_blog")
     * @return Response
     */

    public function index(): Response
    {
        $forRender = [
            'title' => 'Блоги',
            'blogs' => $this->blogRepository->getAllBlogs()
        ];
        return $this->render('admin/blog/index.html.twig', $forRender);
    }

    /**
     * @Route (path="/admin/blog/create", name="admin_blog_create")
     * @param Request $request
     * @return Response
     */
    public function createAction(Request $request): Response
    {
        $blog = new Blog();
        $blogForm = $this->createForm(BlogType::class);
        $blogForm->handleRequest($request);
        if ($blogForm->isSubmitted() && $blogForm->isValid()) {
            $file = $blogForm->get('image')->getData();
            $this->blogRepository->setCreateBlog($blog, $file);
            $this->addFlash('success', 'Блог создан');
            return $this->redirectToRoute('admin_blog');
        }
        $forRender = [
            'title' => 'Создание блога',
            'blog_form' => $blogForm->createView()
        ];
        return $this->render('admin/blog/form_blog.html.twig', $forRender);
    }

    /**
     * @Route (path="/admin/blog/update/{id}", name="admin_blog_update")
     * @param int $id
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function updateAction(int $id, Request $request)
    {
        $blog = $this->blogRepository->getOneBlog($id);
        $blogForm = $this->createForm(BlogType::class, $blog);
        $blogForm->handleRequest($request);
        if ($blogForm->isSubmitted() && $blogForm->isValid()) {
            $newImage = $blogForm->get('image')->getData();
            $this->blogRepository->setUpdateBlog($blog, $newImage);
            $this->addFlash('success', 'Обновление успешно');

            return $this->redirectToRoute('admin_blog');
        }
        $forRender = [
            'title' => 'Редактирование блога',
            'blog_form' => $blogForm->createView()
        ];
        return $this->render('admin/blog/form_blog.html.twig', $forRender);
    }

    /**
     * @Route ("/admin/blog/delete/{id}", name="admin_blog_delete")
     * @param int $id
     * @return RedirectResponse
     */
    public function removeAction(int $id): RedirectResponse
    {
        $blog = $this->blogRepository->getOneBlog($id);
        $fileName = $blog->getImage();
        $this->blogRepository->setDeleteBlog($blog, $fileName);
        $this->addFlash('success', 'Блог удален');
        return $this->redirectToRoute('admin_blog');
    }
}
