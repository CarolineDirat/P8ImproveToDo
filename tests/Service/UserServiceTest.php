<?php

namespace App\Tests\Service;

use App\DataFixtures\AppFixtures;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\UserService;
use Doctrine\Persistence\ManagerRegistry;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @internal
 */
class UserServiceTest extends WebTestCase
{
    use FixturesTrait;

    /**
     * testGetRole.
     *
     * @dataProvider provideUser
     */
    public function testGetRole(string $role, string $username): void
    {
        self::bootKernel();
        $this->loadFixtures([AppFixtures::class]);
        /** @var User $user */
        $user = self::$container
            ->get(UserRepository::class)
            ->findOneBy(['username' => $username])
        ;
        $managerRegistry = $this
            ->getMockBuilder(ManagerRegistry::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $encoder = $this
            ->getMockBuilder(UserPasswordEncoderInterface::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $flashBag = $this
            ->getMockBuilder(FlashBagInterface::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $userService = new UserService($managerRegistry, $encoder, $flashBag); /** @phpstan-ignore-line */
        $roleUser = $userService->getRole($user);

        $this->assertEquals($role, $roleUser);
    }

    /**
     * provideUser.
     *
     * @return array<array<string>>
     */
    public function provideUser(): array
    {
        return [
            ['ROLE_USER', 'user1'],
            ['ROLE_ADMIN', 'admin'],
        ];
    }
}
