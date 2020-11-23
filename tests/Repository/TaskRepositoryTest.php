<?php

namespace App\Tests\Repository;

use App\DataFixtures\AppFixtures;
use App\Repository\TaskRepository;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @internal
 */
class TaskRepositoryTest extends KernelTestCase
{
    use FixturesTrait;

    public function testFindListDone(): void
    {
        self::bootKernel();
        $this->loadFixtures([AppFixtures::class]);
        $tasks = self::$container
            ->get(TaskRepository::class)
            ->findList(true)
        ;
        $tasks = count($tasks);
        $this->assertEquals(25, $tasks);
    }

    public function testFindListNotDone(): void
    {
        self::bootKernel();
        $this->loadFixtures([AppFixtures::class]);
        $tasks = self::$container
            ->get(TaskRepository::class)
            ->findList(false)
        ;
        $tasks = count($tasks);
        $this->assertEquals(26, $tasks);
    }
}
