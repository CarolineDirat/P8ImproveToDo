<?php

namespace App\Form;

use Symfony\Component\Form\FormInterface;

interface AppFormFactoryInterface
{
    /**
     * create.
     *
     * @param string               $name
     * @param object               $entity
     * @param array<string, mixed> $options
     *
     * @return null|FormInterface<string, mixed>
     */
    public function create(string $name, object $entity, array $options = []): ?FormInterface;
}
