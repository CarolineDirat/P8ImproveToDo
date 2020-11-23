<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Validator\ConstraintViolation;

class TaskTest extends WebTestCase
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

    public function getValidTask()
    {
        return (new Task())
            ->setTitle('Titre de la tâche')
            ->setContent('Texte du contenu de la tâche')
        ;
    }

    public function assertHasErrors(Task $task, int $number = 0)
    {
        self::bootKernel();
        $errors = self::$container->get('validator')->validate($task);
        $messages = [];
        /**@var ConstraintViolation $error */
        foreach ($errors as $error) {
            $messages[] = $error->getPropertyPath().' => '.$error->getMessage();
        }
        $this->assertCount($number, $errors, implode(', ', $messages));
    }

    public function testValidTask()
    {
        $this->assertHasErrors($this->getValidTask(), 0);
    }

    public function testInvalidBlankTitleTask()
    {
        $this->assertHasErrors($this->getValidTask()->setTitle(''), 1);
    }

    public function testInvalidBlankContentTask()
    {
        $this->assertHasErrors($this->getValidTask()->setContent(''), 1);
    }
}
