<?php

namespace App\Service;

use App\Entity\Task;
use App\Entity\User;

interface TaskServiceInterface
{
    /**
     * processNewTask.
     *
     * @param Task $task
     * @param User $user
     */
    public function processNewTask(Task $task, User $user): void;

    /**
     * processEditTask.
     *
     * @param Task $task
     */
    public function processEditTask(Task $task): void;
}
