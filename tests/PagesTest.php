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
    public function testRedirectIsSuccessFull(string $url): void
    {
        $this->client->followRedirects();
        $this->client->request('GET', $url);

        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

    /**
     * testForbiddenToAnonymous.
     *
     * @dataProvider provideUrls
     */
    public function testForbiddenToAnonymous(string $url): void
    {
        $this->client->request('GET', $url);

        if ('/login' === $url) {
            $this->assertTrue($this->client->getResponse()->isSuccessful());
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
            ['/'],
            ['/login'],
            ['/tasks'],
            ['/tasks/filter/true'],
            ['/tasks/filter/false'],
            ['/tasks/create'],
            ['/tasks/51/edit'],
            ['/tasks/51/toggle'],
            ['/tasks/51/toggle-ajax'],
            ['/tasks/51/delete'],
            ['/users'],
            ['/users/create'],
            ['/users/1/edit'],
        ];
    }
}
