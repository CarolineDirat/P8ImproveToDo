<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\Form\FormInterface;

interface UserServiceInterface
{
    /**
     * processNewUser
     * Save a new user in database, with form data.
     *
     * @param FormInterface<User> $form
     */
    public function processNewUser(FormInterface $form): void;

    /**
     * processEditUser.
     *
     * @param FormInterface<User> $form
     */
    public function processEditUser(FormInterface $form): void;

    /**
     * getRole
     * Gets user role ('ROLE_ADMIN' or 'ROLE_USER').
     *
     * @param User $user
     *
     * @return string
     */
    public function getRole(User $user): string;
}
