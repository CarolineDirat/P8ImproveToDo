<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @internal
 */
class UserTest extends WebTestCase
{
    public function testUsername(): void
    {
        $user = new User();
        $username = 'username';
        $user->setUsername($username);
        $this->assertEquals($username, $user->getUsername());
    }

    public function testPassword(): void
    {
        $user = new User();
        $user->setPassword('password');
        self::bootKernel();
        $container = self::$container;
        /** @var UserPasswordEncoderInterface $encoder */
        $encoder = $container->get('security.user_password_encoder.generic');
        $password = $encoder->encodePassword($user, $user->getPassword());
        $user->setPassword($password);
        $this->assertEquals($password, $user->getPassword());
    }

    public function testPlainPassword(): void
    {
        $user = new User();
        $plainPassword = 'password';
        $user->setPlainPassword($plainPassword);
        $this->assertEquals($plainPassword, $user->getPlainPassword());
    }

    public function testEmail(): void
    {
        $user = new User();
        $email = 'email.test@domain.com';
        $user->setEmail($email);
        $this->assertEquals($email, $user->getEmail());
    }

    public function testCreatedAt(): void
    {
        $user = new User();
        $this->assertInstanceOf(DateTimeImmutable::class, $user->getCreatedAt());

        $createdAt = new DateTimeImmutable('2020-11-03');
        $user->setCreatedAt($createdAt);
        $this->assertEquals($createdAt, $user->getCreatedAt());
    }

    public function testUpdatedAt(): void
    {
        $user = new User();
        $this->assertInstanceOf(DateTimeImmutable::class, $user->getUpdatedAt());

        $updatedAt = new DateTimeImmutable('2020-11-03');
        $user->setUpdatedAt($updatedAt);
        $this->assertEquals($updatedAt, $user->getUpdatedAt());
    }

    public function testRoles(): void
    {
        $user = new User();
        $this->assertTrue(in_array('ROLE_USER', $user->getRoles()));

        $role = ['ROLE_APP'];
        $user->setRoles($role);
        $this->assertTrue(in_array('ROLE_USER', $user->getRoles()));
        $this->assertTrue(in_array('ROLE_APP', $user->getRoles()));
        $this->assertEquals(['ROLE_APP', 'ROLE_USER'], $user->getRoles());
    }

    public function testAddTask(): void
    {
        $user = new User();
        $task = new Task();
        $user->addTask($task);

        $this->assertContains($task, $user->getTasks(), 'Echec de User::testAddTask()');
    }

    public function testRemoveTask(): void
    {
        $user = new User();
        $task1 = new Task();
        $task2 = new Task();
        $user->addTask($task1);
        $user->addTask($task2);
        $this->assertContains($task2, $user->getTasks());

        $user->removeTask($task2);
        $this->assertNotContains($task2, $user->getTasks(), 'Echec de User::removeTask()');
    }

    public function testEraseCredentials(): void
    {
        $user = new User();
        $password = 'password';
        $user->setPlainPassword($password);
        $this->assertEquals($password, $user->getPlainPassword());

        $user->eraseCredentials();
        $this->assertEquals('xxxxxxxx', $user->getPlainPassword());
    }
}
