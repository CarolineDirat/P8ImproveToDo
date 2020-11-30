<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AppFormFactoryInterface;
use App\Service\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * UserController.
 *
 * @Route("/users", name="user_")
 */
class UserController extends AbstractController
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
     * list all users.
     *
     * @Route("", name="list")
     *
     * @return Response
     */
    public function list(): Response
    {
        return $this->render('user/list.html.twig', ['users' => $this->getDoctrine()->getRepository(User::class)->findAll()]);
    }

    /**
     * create a user.
     *
     * @Route("/create", name="create")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function new(Request $request, UserServiceInterface $userService): Response
    {
        $user = new User();
        $form = $this->appFormFactory->create('user', $user);

        /** @var FormInterface $form */
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userService->processNewUser($form);

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * edit a user.
     *
     * @Route("/{id}/edit", name="edit")
     *
     * @param User                 $user
     * @param Request              $request
     * @param UserServiceInterface $userService
     *
     * @return Response
     */
    public function edit(User $user, Request $request, UserServiceInterface $userService): Response
    {
        $form = $this->appFormFactory->create('user', $user);
        $role = $userService->getRole($user);

        /** @var FormInterface $form */
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userService->processEditUser($form);

            return $this->redirectToRoute('user_list');
        }

        return $this->render(
            'user/edit.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
                'role' => $role,
            ]
        );
    }
}
