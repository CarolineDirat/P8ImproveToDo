<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use DateTime;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{    
    public function testCreatedAt()
    {
        $task = new Task();
        $this->assertInstanceOf(DateTime::class, $task->getCreatedAt());
        $createdAt = new DateTime('2020-11-03');
        $task->setCreatedAt($createdAt);
        $this->assertEquals($createdAt, $task->getCreatedAt());
    }

    public function testUpdatedAt()
    {
        $task = new Task();
        $this->assertInstanceOf(DateTime::class, $task->getUpdatedAt());
        $updatedAt = new DateTime('2020-11-03');
        $task->setUpdatedAt($updatedAt);
        $this->assertEquals($updatedAt, $task->getUpdatedAt());
    }

    public function testTitle()
    {
        $task = new Task();
        $title = 'Titre de la tâche';
        $task->setTitle($title);
        $this->assertEquals($title, $task->getTitle());
    }

    public function testContent()
    {
        $task = new Task();
        $content = 'Texte du contenu de la tâche';
        $task->setContent($content);
        $this->assertEquals($content, $task->getContent());
    }

    public function testIsDone()
    {
        $task = new Task();
        $this->assertEquals(false, $task->isDone());
        $task->toggle(true);
        $this->assertEquals(true, $task->isDone());
    }
}
