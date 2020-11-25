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
}
