<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    /**
     * list all tasks.
     *
     * @Route("/tasks", name="task_list")
     *
     * @return Response
     */
    public function list(): Response
    {
        return $this->render('task/list.html.twig', ['tasks' => $this->getDoctrine()->getRepository(Task::class)->findAll()]);
    }
    
    /**
     * list done tasks
     * 
     * @Route("/tasks/done", name="task_list_done")
     *
     * @return Response
     */
    public function listDone(TaskRepository $taskRepository): Response
    {
        return $this->render(
            'task/list.html.twig',
            [
                'tasks' => $taskRepository->findList(true),
                'title' => 'Liste des tâches terminées',
            ]
        );
    }

    /**
     * create a task.
     *
     * @Route("/tasks/create", name="task_create")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function create(Request $request): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($task);
            $em->flush();

            $this->addFlash('success', 'La tâche a bien été ajoutée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * edit a task.
     *
     * @Route("/tasks/{id}/edit", name="task_edit")
     *
     * @param Task    $task
     * @param Request $request
     *
     * @return Response
     */
    public function edit(Task $task, Request $request): Response
    {
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'La tâche a bien été modifiée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

    /**
     * toggleState: edit task's status.
     *
     * @Route("/tasks/{id}/toggle/{fromList}", name="task_toggle")
     *
     * @param Task $task
     * @param string|null $formListIsDone
     *
     * @return Response
     */
    public function toggleState(Task $task, ?string $fromList = null): Response
    {
        $task->toggle(!$task->isDone());
        $this->getDoctrine()->getManager()->flush();

        $message = sprintf('La tâche %s a bien été marquée comme non terminée.', $task->getTitle());

        if (true === $task->isDone()) {
            $message = sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle());
        }

        $this->addFlash('success', $message);

        switch ($fromList) {
            case 'done':
                return $this->redirectToRoute('task_list_done');
            
            case 'waiting':
                return $this->redirectToRoute('task_list_waiting');
        }

        return $this->redirectToRoute('task_list');
    }

    /**
     * delete a task.
     *
     * @Route("/tasks/{id}/delete", name="task_delete")
     *
     * @param Task $task
     *
     * @return Response
     */
    public function delete(Task $task): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($task);
        $em->flush();

        $this->addFlash('success', 'La tâche a bien été supprimée.');

        return $this->redirectToRoute('task_list');
    }
}
