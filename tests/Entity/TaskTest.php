<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class TaskTest extends TestCase
{
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
}
