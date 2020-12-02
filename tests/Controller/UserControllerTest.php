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
class UserControllerTest extends WebTestCase
{
    use FixturesTrait;
    use LoginTrait;

    private KernelBrowser $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->loadFixtures([AppFixtures::class]);
        $this->logInAdmin();
    }

    public function testList(): void
    {
        $crawler = $this->client->request('GET', '/users');

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertSame('Liste des utilisateurs', $crawler->filter('h1')->text());
    }

    public function testListForbiddenToUser(): void
    {
        $this->client->request('GET', '/logout');
        $this->logInUser();
        $this->client->request('GET', '/users');

        $this->assertFalse($this->client->getResponse()->isSuccessful());
    }

    public function testCreate(): void
    {
        $crawler = $this->client->request('GET', '/users/create');
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertSame('Créer un utilisateur', $crawler->filter('h1')->text());
    }

    public function testCreateForbiddenToUser(): void
    {
        $this->client->request('GET', '/logout');
        $this->logInUser();
        $this->client->request('GET', '/users/create');

        $this->assertFalse($this->client->getResponse()->isSuccessful());
    }

    public function testCreateUserForm(): void
    {
        $crawler = $this->client->request('GET', '/users/create');
        $buttonCrawlerNode = $crawler->selectButton('Ajouter');
        $form = $buttonCrawlerNode->form([
            'user[username]' => 'user',
            'user[password][first]' => 'password',
            'user[password][second]' => 'password',
            'user[email]' => 'user@mail.com',
            'user[role]' => 'ROLE_USER',
        ]);
        $this->client->submit($form);
        $this->assertResponseRedirects('/users');
        $this->client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');
    }

    public function testCreateUserWithBadRole(): void
    {
        $crawler = $this->client->request('GET', '/users/create');
        $buttonCrawlerNode = $crawler->selectButton('Ajouter');

        $this->expectException('InvalidArgumentException');

        $buttonCrawlerNode->form([
            'user[username]' => 'user',
            'user[password][first]' => 'password',
            'user[password][second]' => 'password',
            'user[email]' => 'user@mail.com',
            'user[role]' => 'admin',
        ]);
    }

    public function testEdit(): void
    {
        $crawler = $this->client->request('GET', '/users/1/edit');
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertSame('Modifier user1', $crawler->filter('h1')->text());
    }

    public function testEditForbiddenToUser(): void
    {
        $this->client->request('GET', '/logout');
        $this->logInUser();
        $this->client->request('GET', '/users/1/edit');

        $this->assertFalse($this->client->getResponse()->isSuccessful());
    }

    public function testEditUserForm(): void
    {
        $crawler = $this->client->request('GET', '/users/1/edit');
        $buttonCrawlerNode = $crawler->selectButton('Modifier');
        $form = $buttonCrawlerNode->form([
            'user[username]' => 'user',
            'user[password][first]' => 'password',
            'user[password][second]' => 'password',
            'user[email]' => 'user@mail.com',
            'user[role]' => 'ROLE_USER',
        ]);
        $this->client->submit($form);
        $this->assertResponseRedirects('/users');
        $this->client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');
    }
}
