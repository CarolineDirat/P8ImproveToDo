<?php

namespace App\Form;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class AppFormFactory implements AppFormFactoryInterface
{
    private FormFactoryInterface $formFactory;

    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * create.
     *
     * @param string               $name
     * @param object               $entity
     * @param array<string, mixed> $options
     *
     * @return null|FormInterface<string, mixed>
     */
    public function create(string $name, object $entity, array $options = []): ?FormInterface
    {
        $formTypes = [
            'user' => UserType::class,
            'task' => TaskType::class,
        ];

        $formType = $formTypes[$name];

        if (!empty($formType)) {
            return $this->formFactory->create($formType, $entity, $options);
        }

        return null;
    }
}
