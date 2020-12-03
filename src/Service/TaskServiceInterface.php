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
    public function processNew(Task $task, User $user): void;

    /**
     * processEditTask.
     *
     * @param Task $task
     */
    public function processEdit(Task $task): void;

    /**
     * processDelete.
     *
     * @param Task $task
     */
    public function processDelete(Task $task): void;
}
