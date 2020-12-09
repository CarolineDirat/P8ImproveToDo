<?php

namespace App\Service;

use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
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
     * @param FormInterface<User> $form
     */
    public function processNewUser(FormInterface $form): void
    {
        /** @var User $user */
        $user = $form->getData();
        $role = $form->get('role')->getData();

        $this->flashBag->add('error', "L'utilisateur n'a pas pu être ajouté.");

        if (in_array($role, self::APP_ROLES)) {
            $user->setRoles([$role]);

            $em = $this->managerRegistry->getManager();
            $em->persist($user);
            $em->flush();

            $this->flashBag->clear();
            $this->flashBag->add('success', 'L\'utilisateur "'.$user->getUsername().'" a bien été ajouté.');
        }
    }

    /**
     * getRole.
     *
     * @param User $user
     *
     * @return string
     */
    public function getRole(User $user): string
    {
        $roles = $user->getRoles();
        $role = 'ROLE_USER';
        if (in_array('ROLE_ADMIN', $roles, true)) {
            $role = 'ROLE_ADMIN';
        }

        return $role;
    }

    /**
     * processEditUser.
     *
     * @param FormInterface<User> $form
     */
    public function processEditUser(FormInterface $form): void
    {
        /** @var User $user */
        $user = $form->getData();
        $role = $form->get('role')->getData();

        $this->flashBag->add('error', "L'utilisateur n'a pas pu être modifié");

        if (in_array($role, self::APP_ROLES)) {
            $em = $this->managerRegistry->getManager();

            $user->setRoles([$role]);
            $password = $this->encoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $user->setUpdatedAt(new DateTimeImmutable());

            $em->flush();

            $this->flashBag->clear();
            $this->flashBag->add('success', 'L\'utilisateur "'.$user->getUsername().'" a bien été modifié');
        }
    }
}
