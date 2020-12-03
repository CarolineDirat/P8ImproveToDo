<?php

namespace App\Tests\Security;

use App\DataFixtures\AppFixtures;
use App\Entity\Task;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Security\TaskVoter;
use App\Tests\LoginTrait;
use Doctrine\Persistence\ManagerRegistry;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use LogicException;
use Prophecy\Argument\Token\TokenInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface as TokenTokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Security;

/**
 * @internal
 */
class TaskVoterTest extends WebTestCase
{
    use LoginTrait;
    use FixturesTrait;

    /**
     * taskVoter.
     *
     * @var TaskVoterPublic
     */
    private TaskVoterPublic $taskVoter;

    public function setup(): void
    {
        $managerRegistry = $this
            ->getMockBuilder(ManagerRegistry::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $security = $this
            ->getMockBuilder(Security::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        /* @var TaskVoterPublic $tasksVoter */
        $this->taskVoter = new TaskVoterPublic($managerRegistry, $security); /* @phpstan-ignore-line */
    }

    public function testSupportWithWrongSubject(): void
    {
        $this->assertFalse($this->taskVoter->supports('delete', new User()));
    }

    public function testVoteOnAttributeWithWrongToken(): void
    {
        /** @var TokenInterface $token */
        $token = $this
            ->getMockBuilder(TokenTokenInterface::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $task = new Task();

        $this->assertFalse($this->taskVoter->voteOnAttribute('delete', $task, $token)); /* @phpstan-ignore-line */
    }

    public function testLogicExceptionThrowByVoteOnAttribute(): void
    {
        $this->loadFixtures([AppFixtures::class]);
        $user = self::$container->get(UserRepository::class)->findOneBy(['username' => 'user1']);
        $firewallName = 'main';
        $token = new UsernamePasswordToken($user, null, $firewallName, $user->getRoles());

        $task = new Task();

        $this->expectException(LogicException::class);
        $this->taskVoter->voteOnAttribute('wrong_attribute', $task, $token);
    }
}
