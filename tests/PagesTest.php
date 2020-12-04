<?php

namespace App\Tests;

use App\DataFixtures\AppFixtures;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @internal
 * @coversNothing
 */
class PagesTest extends WebTestCase
{
    use FixturesTrait;

    private KernelBrowser $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->loadFixtures([AppFixtures::class]);
    }

    /**
     * testPageIsSuccessFull.
     *
     * @dataProvider provideUrls
     *
     * @param string $url
     */
    public function testRedirectIsSuccessFull(string $method, string $url): void
    {
        $this->client->followRedirects();
        $this->client->request($method, $url);

        $this->assertResponseIsSuccessful();
    }

    /**
     * testForbiddenToAnonymous.
     *
     * @dataProvider provideUrls
     */
    public function testForbiddenToAnonymous(string $method, string $url): void
    {
        $this->client->request($method, $url);

        if ('/login' === $url) {
            $this->assertResponseIsSuccessful();
        }
        if ('/login' !== $url) {
            $this->assertFalse($this->client->getResponse()->isSuccessful());
        }
    }

    /**
     * provideUrls.
     *
     * @return array<array<string>>
     */
    public function provideUrls(): array
    {
        return [
            ['GET', '/'],
            ['GET', '/login'],
            ['GET', '/tasks'],
            ['GET', '/tasks/filter/true'],
            ['GET', '/tasks/filter/false'],
            ['GET', '/tasks/create'],
            ['GET', '/tasks/51/edit'],
            ['GET', '/tasks/51/toggle'],
            ['GET', '/tasks/51/toggle-ajax'],
            ['POST', '/tasks/51/delete'],
            ['GET', '/users'],
            ['GET', '/users/create'],
            ['GET', '/users/1/edit'],
        ];
    }
}
