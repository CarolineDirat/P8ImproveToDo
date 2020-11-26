<?php

namespace App\Service;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserService implements UserServiceInterface
{
    const APP_ROLES = ['ROLE_USER', 'ROLE_ADMIN'];

    /**
     * managerRegistry.
     *
     * @var ManagerRegistry
     */
    private ManagerRegistry $managerRegistry;

    /**
     * encoder.
     *
     * @var UserPasswordEncoderInterface
     */
    private UserPasswordEncoderInterface $encoder;

    /**
     * session.
     *
     * @var FlashBagInterface
     */
    private FlashBagInterface $flashBag;

    public function __construct(ManagerRegistry $managerRegistry, UserPasswordEncoderInterface $encoder, FlashBagInterface $flashBag)
    {
        $this->managerRegistry = $managerRegistry;
        $this->encoder = $encoder;
        $this->flashBag = $flashBag;
    }

    /**
     * processNewUser
     * Save a new user in database, with form data.
     *
     * @param FormInterface<mixed> $form
     */
    public function processNewUser(FormInterface $form): void
    {
        $user = $form->getData();
        $em = $this->managerRegistry->getManager();

        $password = $this->encoder->encodePassword($user, $user->getPassword());
        $user->setPassword($password);

        $role = $form->get('role')->getData();

        if (in_array($role, self::APP_ROLES)) {
            $user->setRoles([$role]);

            $em->persist($user);
            $em->flush();

            $this->flashBag->add('success', "L'utilisateur a bien été ajouté.");
        } else {
            $this->flashBag->add('error', "L'utilisateur n'a pas pu être ajouté.");
        }
    }
}
