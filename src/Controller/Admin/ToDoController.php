<?php

namespace App\Controller\Admin;

use App\Entity\Task;
use App\Form\TaskType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ToDoController extends AbstractController
{
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
     * @param EntityManagerInterface $entityManager
     * @return void
     */
    public function createAction(Request $request, EntityManagerInterface $entityManager): Response
    {
        $task = new Task();
        $taskForm = $this->createForm(TaskType::class);
        $taskForm->handleRequest($request);
        if ($taskForm->isSubmitted() && $taskForm->isValid()) {
            $task->setTitle($taskForm->get('title')->getData());
            $task->setDescription($taskForm->get('description')->getData());
            $task->setDueDate($taskForm->get('dueDate')->getData());
            $task->setCreatedAt(date_create_immutable('now'));
            $entityManager->persist($task);
            $entityManager->flush();

            $this->addFlash('success', 'задание создано');
            return $this->redirectToRoute('admin_todo');
        }
        $forRender = [
            'title'=>'создание задания',
            'task_form' => $taskForm->createView()
        ];
        return $this->render('admin/to_do/task_form.html.twig', $forRender);
    }

}
