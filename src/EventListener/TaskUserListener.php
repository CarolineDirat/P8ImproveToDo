<?php

namespace App\EventListener;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class TaskUserListener
{
    public function postLoad(Task $task, LifecycleEventArgs $event): void
    {
        if (null === $task->getUser()) {
            /** @var User $anonymous */
            $anonymous = $event->getObjectManager()->getRepository(User::class)->findOneBy(['username' => 'Anonymous']);
            $task->setUser($anonymous);
        }
    }
}
