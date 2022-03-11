<?php

namespace App\Controller\Admin;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ToDoController extends AbstractController
{
    private $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;

    }

    /**
     * @Route("/admin/todo", name="admin_todo")
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $tasks = $entityManager->getRepository(Task::class)->findAll();
        $forRender = [
            'title' => 'список дел',
            'tasks' => $tasks
        ];
        return $this->render('admin/to_do/index.html.twig', $forRender);
    }

    /**
     * @Route("/admin/todo/create", name="admin_todo_create")
     * @param Request $request
     * @return void
     */
    public function createAction(Request $request): Response
    {
        $task = new Task();
        $taskForm = $this->createForm(TaskType::class);
        $taskForm->handleRequest($request);
        if ($taskForm->isSubmitted() && $taskForm->isValid()) {
            $this->taskRepository->setCreateTask($task, $taskForm);
            $this->addFlash('success', 'задание создано');
            return $this->redirectToRoute('admin_todo');
        }

        $forRender = [
            'title' => 'создание задания',
            'task_form' => $taskForm->createView()
        ];
        return $this->render('admin/to_do/task_form.html.twig', $forRender);
    }

    /**
     * @Route (path="/admin/todo/update/{id}", name="admin_todo_update")
     * @param int $id
     * @param Request $request
     * @return Response
     */
    public function updateAction(int $id, Request $request): Response
    {
        $task = $this->taskRepository->getOneTask($id);
        $taskForm = $this->createForm(TaskType::class, $task);
        $taskForm->handleRequest($request);
        if ($taskForm->isSubmitted() && $taskForm->isValid()) {
            $this->taskRepository->setUpdateTask($task, $taskForm);

            $this->addFlash('success', 'задание успешно обновлено');
            return $this->redirectToRoute('admin_todo');
        }
        $forRender = [
            'title' => 'изменение задания',
            'task_form' => $taskForm->createView()
        ];
        return $this->render('admin/to_do/task_form.html.twig', $forRender);
    }

    /**
     * @Route ("/admin/todo/delete/{id}", name="admin_todo_delete")
     * @param int $id
     * @return RedirectResponse
     */
    public function removeAction(int $id): RedirectResponse
    {
        $task = $this->taskRepository->getOneTask($id);
        $this->taskRepository->setDeleteTask($task);
        $this->addFlash('success', 'Задание удалено');
        return $this->redirectToRoute('admin_todo');
    }

}
