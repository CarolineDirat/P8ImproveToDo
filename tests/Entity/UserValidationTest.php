<?php

namespace App\Tests\Entity;

use App\DataFixtures\AppFixtures;
use App\Entity\User;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Validator\ConstraintViolation;

/**
 * @internal
 */
class UserValidationTest extends WebTestCase
{
    use FixturesTrait;

    public function getValidUser(): User
    {
        return (new User())
            ->setUsername('username')
            ->setPlainPassword('password')
            ->setEmail('username@mail.com')
        ;
    }

    public function assertHasErrors(User $user, int $number = 0): void
    {
        self::bootKernel();
        $errors = self::$container->get('validator')->validate($user);
        $messages = [];
        /** @var ConstraintViolation $error */
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

    public function testInvalidUniqueUsername(): void
    {
        self::bootKernel();
        $this->loadFixtures([AppFixtures::class]);
        $this->assertHasErrors($this->getValidUser()->setUsername('user1'), 1);
    }

    public function testInvalidBlankPlainPassword(): void
    {
        $this->assertHasErrors($this->getValidUser()->setPlainPassword(''), 2);
    }

    public function testInvalidMinLengthPlainPassword(): void
    {
        $this->assertHasErrors($this->getValidUser()->setPlainPassword('000'), 1);
    }

    public function testInvalidMaxLengthPlainPassword(): void
    {
        $password = 'x';
        for ($i = 0; $i < 5000; ++$i) {
            $password .= (string) $i;
        }

        $this->assertHasErrors($this->getValidUser()->setPlainPassword($password), 1);
    }

    public function testInvalidBlankEmail(): void
    {
        $this->assertHasErrors($this->getValidUser()->setEmail(''), 1);
    }

    public function testInvalidEmail(): void
    {
        $this->assertHasErrors($this->getValidUser()->setEmail('email'), 1);
    }

    public function testInvalidUniqueEmail(): void
    {
        self::bootKernel();
        $this->loadFixtures([AppFixtures::class]);
        $this->assertHasErrors($this->getValidUser()->setEmail('user1@email.com'), 1);
    }
}
