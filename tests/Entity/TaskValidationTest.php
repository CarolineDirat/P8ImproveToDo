<?php

namespace App\Tests\Entity;

use App\DataFixtures\AppFixtures;
use App\Entity\Task;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Validator\ConstraintViolation;

/**
 * @internal
 */
class TaskValidationTest extends WebTestCase
{
    use FixturesTrait;

    public function getValidTask(): Task
    {
        return (new Task())
            ->setTitle('Titre de la tâche')
            ->setContent('Texte du contenu de la tâche')
        ;
    }

    public function assertHasErrors(Task $task, int $number = 0): void
    {
        self::bootKernel();
        $errors = self::$container->get('validator')->validate($task);
        $messages = [];
        /** @var ConstraintViolation $error */
        foreach ($errors as $error) {
            $messages[] = $error->getPropertyPath().' => '.$error->getMessage();
        }

        $this->assertCount($number, $errors, implode(', ', $messages));
    }

    public function testValidTask(): void
    {
        $this->assertHasErrors($this->getValidTask(), 0);
    }

    public function testInvalidBlankTitleTask(): void
    {
        $this->assertHasErrors($this->getValidTask()->setTitle(''), 1);
    }

    public function testInvalidLengthTitleTask(): void
    {
        $this->assertHasErrors(
            $this->getValidTask()->setTitle('Titre de la tâche de plus de 40 caractères.'),
            1
        );
    }

    public function testInvalidUniqueTitleTask(): void
    {
        self::bootKernel();
        $this->loadFixtures([AppFixtures::class]);
        $this->assertHasErrors($this->getValidTask()->setTitle('Tâche n°1'), 1);
    }

    public function testInvalidBlankContentTask(): void
    {
        $this->assertHasErrors($this->getValidTask()->setContent(''), 1);
    }
}
