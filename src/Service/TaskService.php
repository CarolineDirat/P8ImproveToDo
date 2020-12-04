<?php

namespace App\Service;

use App\Entity\Task;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class TaskService implements TaskServiceInterface
{
    /**
     * managerRegistry.
     *
     * @var ManagerRegistry
     */
    private ManagerRegistry $managerRegistry;

    /**
     * session.
     *
     * @var FlashBagInterface
     */
    private FlashBagInterface $flashBag;

    public function __construct(ManagerRegistry $managerRegistry, FlashBagInterface $flashBag)
    {
        $this->managerRegistry = $managerRegistry;
        $this->flashBag = $flashBag;
    }

    /**
     * processNewTask.
     *
     * @param Task $task
     * @param User $user
     */
    public function processNew(Task $task, User $user): void
    {
        $task->setUser($user);
        $em = $this->managerRegistry->getManager();

        $em->persist($task);
        $em->flush();

        $this->flashBag->add('success', 'La tâche "'.$task->getTitle().'" a bien été ajoutée.');
    }

    /**
     * processEditTask.
     *
     * @param Task $task
     */
    public function processEdit(Task $task): void
    {
        $task->setUpdatedAt(new DateTimeImmutable());
        $this->managerRegistry->getManager()->flush();

        $this->flashBag->add('success', 'La tâche "'.$task->getTitle().'" a bien été modifiée.');
    }

    /**
     * processDelete.
     *
     * @param Task $task
     */
    public function processDelete(Task $task): void
    {
        $title = $task->getTitle();
        $em = $this->managerRegistry->getManager();
        $em->remove($task);
        $em->flush();

        $this->flashBag->add('success', 'La tâche "'.$title.'" a bien été supprimée.');
    }
}
