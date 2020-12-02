<?php

namespace App\Tests\Entity;

use App\DataFixtures\AppFixtures;
use App\Entity\Task;
use App\Entity\User;
use DateTimeImmutable;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Validator\ConstraintViolation;

/**
 * @internal
 */
class TaskTest extends WebTestCase
{
    use FixturesTrait;

    public function testCreatedAt(): void
    {
        $task = new Task();
        $this->assertInstanceOf(DateTimeImmutable::class, $task->getCreatedAt());
        $createdAt = new DateTimeImmutable('2020-11-03');
        $task->setCreatedAt($createdAt);

        $this->assertEquals($createdAt, $task->getCreatedAt());
    }

    public function testUpdatedAt(): void
    {
        $task = new Task();
        $this->assertInstanceOf(DateTimeImmutable::class, $task->getUpdatedAt());
        $updatedAt = new DateTimeImmutable('2020-11-03');
        $task->setUpdatedAt($updatedAt);

        $this->assertEquals($updatedAt, $task->getUpdatedAt());
    }

    public function testTitle(): void
    {
        $task = new Task();
        $title = 'Titre de la tâche';
        $task->setTitle($title);

        $this->assertEquals($title, $task->getTitle());
    }

    public function testContent(): void
    {
        $task = new Task();
        $content = 'Texte du contenu de la tâche';
        $task->setContent($content);

        $this->assertEquals($content, $task->getContent());
    }

    public function testUser(): void
    {
        $task = new Task();
        $user = new User();
        $task->setUser($user);

        $this->assertEquals($user, $task->getUser());
    }

    public function testIsDone(): void
    {
        $task = new Task();
        $this->assertEquals(false, $task->isDone());
        $task->toggle(true);

        $this->assertEquals(true, $task->isDone());
    }

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
