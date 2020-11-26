<?php

namespace App\Tests\Entity;

use App\Entity\User;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Validator\ConstraintViolation;

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
        $password = password_hash('password', PASSWORD_BCRYPT);
        $user->setPassword($password);
        $this->assertEquals($password, $user->getPassword());
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

    public function getValidUser(): User
    {
        return (new User())
            ->setUsername('username')
            ->setPassword('password')
            ->setEmail('username@mail.com')
        ;
    }

    public function assertHasErrors(User $user, int $number = 0): void
    {
        self::bootKernel();
        $errors = self::$container->get('validator')->validate($user);
        $messages = [];
        /*@var ConstraintViolation $error */
        foreach ($errors as $error) {
            $messages[] = $error->getPropertyPath().' => '.$error->getMessage();
        }
        $this->assertCount($number, $errors, implode(', ', $messages));
    }

    public function testValidUser(): void
    {
        $this->assertHasErrors($this->getValidUser(), 0);
    }

    public function testInvalidBlankUsername(): void
    {
        $this->assertHasErrors($this->getValidUser()->setUsername(''), 2);
    }

    public function testInvalidLengthUsername(): void
    {
        $this->assertHasErrors($this->getValidUser()->setUsername('Jo'), 1);
    }

    public function testInvalidBlankPassword(): void
    {
        $this->assertHasErrors($this->getValidUser()->setPassword(''), 2);
    }

    public function testInvalidLengthPassword(): void
    {
        $this->assertHasErrors($this->getValidUser()->setPassword('000'), 1);
    }

    public function testInvalidBlankEmail(): void
    {
        $this->assertHasErrors($this->getValidUser()->setEmail(''), 1);
    }

    public function testInvalidEmail(): void
    {
        $this->assertHasErrors($this->getValidUser()->setEmail('email'), 1);
    }
}
