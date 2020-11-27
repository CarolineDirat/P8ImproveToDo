<?php

namespace App\Tests\Controller;

use App\DataFixtures\AppFixtures;
use App\Tests\LoginTrait;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @internal
 */
class DefaultControllerTest extends WebTestCase
{
    use FixturesTrait;
    use LoginTrait;

    private KernelBrowser $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testIndexWithRedirection(): void
    {
        $this->client->request('GET', '/');
        $this->client->followRedirect();

        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

    public function testIndex(): void
    {
        $this->loadFixtures([AppFixtures::class]);
        $this->logInUser();
        $crawler = $this->client->request('GET', '/');

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertSame('Bienvenue sur Todo List, ', $crawler->filter('h1')->text('error', false));
    }
}
