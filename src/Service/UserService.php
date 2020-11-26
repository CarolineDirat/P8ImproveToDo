<?php

namespace App\Service;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
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
     * @var Session<mixed>
     */
    private Session $session;

    public function __construct(ManagerRegistry $managerRegistry, UserPasswordEncoderInterface $encoder)
    {
        $this->managerRegistry = $managerRegistry;
        $this->encoder = $encoder;
        $this->session = new Session(new NativeSessionStorage(), new AttributeBag());
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

            $this->session->getFlashBag()->add('success', "L'utilisateur a bien été ajouté.");
        } else {
            $this->session->getFlashBag()->add('error', "L'utilisateur n'a pas pu être ajouté.");
        }
    }
}
