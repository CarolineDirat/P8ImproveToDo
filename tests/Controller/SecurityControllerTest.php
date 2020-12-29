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
class SecurityControllerTest extends WebTestCase
{
    use FixturesTrait;
    use LoginTrait;

    private KernelBrowser $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->loadFixtures([AppFixtures::class]);
    }

    public function testLoginFormWithGoodCredentials(): void
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $form['_username'] = 'user1';
        $form['_password'] = 'password';
        $crawler = $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isRedirect());
        $crawler = $this->client->followRedirect();
        $this->assertEquals('Bienvenue sur Todo List, ', $crawler->filter('h1')->text(null, false));
    }

    public function testLoginFormWithBadCredentials(): void
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $form['_username'] = 'user1';
        $form['_password'] = 'xxxxxxxx';
        $crawler = $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isRedirect());
        $crawler = $this->client->followRedirect();
        $this->assertEquals('Se connecter', $crawler->filter('button')->text(null, false));
    }

    public function testLoginFormWithBadCsrfToken(): void
    {
        $crawler = $this->client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $form['_username'] = 'user1';
        $form['_password'] = 'password';
        $form['_csrf_token'] = 'xxxx';
        $crawler = $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isRedirect());
        $this->client->followRedirect();

        $this->assertSelectorExists('.alert.alert-danger');
        $this->assertEquals('Se connecter', $this->client->getCrawler()->filter('button')->text(null, false));
        $this->assertNotEquals('Bienvenue sur Todo List, ', $this->client->getCrawler()->filter('button')->text(null, false));
    }

    public function testLinkLogout(): void
    {
        $this->logInUser();
        $this->client->request('GET', '/');
        $this->client->clickLink('Se déconnecter');
        $this->client->followRedirect();

        $this->assertSelectorTextContains('button', 'Se connecter');
    }

    public function testLogout(): void
    {
        $this->logInUser();
        $this->client->request('GET', '/logout');
        $this->assertTrue($this->client->getResponse()->isRedirect());
        $this->client->followRedirect();

        $this->assertSelectorTextContains('button', 'Se connecter');
    }
}
