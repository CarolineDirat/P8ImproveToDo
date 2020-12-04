<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\User;
use App\Form\AppFormFactoryInterface;
use App\Repository\TaskRepository;
use App\Service\TaskServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    /**
     * addFormFactory.
     *
     * @var AppFormFactoryInterface
     */
    private AppFormFactoryInterface $appFormFactory;

    public function __construct(AppFormFactoryInterface $appFormFactory)
    {
        $this->appFormFactory = $appFormFactory;
    }

    /**
     * list all tasks.
     *
     * @Route("/tasks", name="task_list_all")
     *
     * @return Response
     */
    public function listAll(): Response
    {
        return $this->render(
            'task/list.html.twig',
            [
                'tasks' => $this
                    ->getDoctrine()
                    ->getRepository(Task::class)
                    ->findBy([], ['updatedAt' => 'DESC']),
            ]
        );
    }

    /**
     * list done or waiting tasks.
     *
     * @Route("/tasks/filter/{isDone}", name="task_list")
     *
     * @return Response
     */
    public function list(TaskRepository $taskRepository, string $isDone): Response
    {
        $title = 'Liste des tâches non terminées';
        $state = false;

        if ('true' === $isDone) {
            $title = 'Liste des tâches terminées';
            $state = true;
        }

        return $this->render(
            'task/list_is.html.twig',
            [
                'tasks' => $taskRepository->findList($state),
                'title' => $title,
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
    public function create(Request $request, TaskServiceInterface $taskService): Response
    {
        $task = new Task();
        $form = $this->appFormFactory->create('task', $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $this->getUser();
            $taskService->processNew($task, $user);

            return $this->redirectToRoute('task_list_all');
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * edit a task.
     *
     * @Route("/tasks/{id}/edit", name="task_edit")
     *
     * @param Task                 $task
     * @param Request              $request
     * @param TaskServiceInterface $taskService
     *
     * @return Response
     */
    public function edit(
        Task $task,
        Request $request,
        TaskServiceInterface $taskService
    ): Response {
        $form = $this->appFormFactory->create('task', $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $taskService->processEdit($task);

            return $this->redirectToRoute('task_list_all');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

    /**
     * toggleState: edit task's status.
     *
     * @Route("/tasks/{id}/toggle", name="task_toggle")
     *
     * @param Task $task
     *
     * @return Response
     */
    public function toggleState(Task $task): Response
    {
        $task->toggle(!$task->isDone());
        $this->getDoctrine()->getManager()->flush();

        $message = sprintf('La tâche %s a bien été marquée comme non terminée.', $task->getTitle());

        if (true === $task->isDone()) {
            $message = sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle());
        }

        $this->addFlash('success', $message);

        return $this->redirectToRoute('task_list_all');
    }

    /**
     * toggleState: edit task's status form AJAX request.
     * it's called by tasks list page for done or not done tasks.
     *
     * @Route("/tasks/{id}/toggle-ajax", name="task_toggle_ajax")
     *
     * @param Task    $task
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function toggleStateAjax(Task $task, Request $request): JsonResponse
    {
        $data = json_decode((string) $request->getContent(), true);

        if ($this->isCsrfTokenValid('toggle-token-'.$task->getId(), $data['_token'])) {
            $task->toggle(!$task->isDone());
            $this->getDoctrine()->getManager()->flush();

            $message = sprintf('La tâche %s est maintenant marquée comme non terminée.', $task->getTitle());

            if (true === $task->isDone()) {
                $message = sprintf('La tâche %s est maintenant marquée comme faite.', $task->getTitle());
            }

            return $this->json(
                [
                    'message' => $message,
                    'taskId' => $task->getId(),
                ],
                200,
                ['Content-Type' => 'application/json']
            );
        }

        return $this->json(
            ['message' => 'Accès refusé.'],
            403,
            ['Content-Type' => 'application/json']
        );
    }

    /**
     * delete a task.
     *
     * @Route("/tasks/{id}/delete", name="task_delete", methods={"GET", "POST"})
     *
     * @param Task $task
     *
     * @return Response
     */
    public function delete(Task $task, Request $request, TaskServiceInterface $taskService): Response
    {
        $submittedToken = $request->request->get('token');
        if ($this->isCsrfTokenValid('delete-task-'.$task->getId(), $submittedToken)) {
            /** @var User $user */
            $user = $this->getUser();
            if ($user->hasRole('ROLE_ADMIN')) {
                $message = 'Suppression refusée : vous ne pouvez supprimer que vos propres tâches et celles de l\'utilisateur "Anonymous".';
                $this->denyAccessUnlessGranted('delete', $task, $message);
            }

            $this->denyAccessUnlessGranted('delete', $task, 'Suppression refusée : vous ne pouvez supprimer que vos propres tâches.');

            $taskService->processDelete($task);
        } else {
            $this->addFlash('error', 'La suppression est refusée. Veuillez vous connecter.');
        }

        return $this->redirectToRoute('task_list_all');
    }
}
