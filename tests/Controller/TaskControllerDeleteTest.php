<?php

namespace App\Tests\Controller;

use App\DataFixtures\AppFixtures;
use App\Tests\LoginTrait;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @internal
 */
class TaskControllerDeleteTest extends WebTestCase
{
    use FixturesTrait;
    use LoginTrait;

    private KernelBrowser $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->loadFixtures([AppFixtures::class]);
    }

    public function testDeleteByUserAuthor(): void
    {
        $this->logInUser();

        $crawler = $this->client->request('GET', '/tasks');
        $form = $crawler->filter('button[id="delete-task-15"]')->form();
        $this->client->submit($form);

        $this->assertResponseRedirects('/tasks');
        $this->client->followRedirect();

        $this->assertSelectorExists('.alert.alert-success');
    }

    public function testDeleteByAdminAuthor(): void
    {
        $this->logInAdmin();
        $crawler = $this->client->request('GET', '/tasks');
        $form = $crawler->filter('button[id="delete-task-25"]')->form();
        $this->client->submit($form);

        $this->assertResponseRedirects('/tasks');
        $this->client->followRedirect();

        $this->assertSelectorExists('.alert.alert-success');
    }

    public function testDeleteAnonymousByAdmin(): void
    {
        $this->logInAdmin();
        $crawler = $this->client->request('GET', '/tasks');
        $form = $crawler->filter('button[id="delete-task-5"]')->form();
        $this->client->submit($form);

        $this->assertResponseRedirects('/tasks');
        $this->client->followRedirect();

        $this->assertSelectorExists('.alert.alert-success');
    }

    public function testDeleteForbiddenToUser(): void
    {
        $this->logInUser();
        $this->client->catchExceptions(false);

        $crawler = $this->client->request('GET', '/tasks');
        $form = $crawler->filter('button[id="delete-task-45"]')->form();

        $this->expectException(AccessDeniedException::class);
        $this->expectExceptionCode(403);
        $this->expectExceptionMessage('Suppression refusée : vous ne pouvez supprimer que vos propres tâches.');

        $this->client->submit($form);
    }

    public function testDeleteForbiddenToAdmin(): void
    {
        $this->logInAdmin();
        $this->client->catchExceptions(false);

        $crawler = $this->client->request('GET', '/tasks');
        $form = $crawler->filter('button[id="delete-task-45"]')->form();

        $this->expectException(AccessDeniedException::class);
        $this->expectExceptionCode(403);
        $this->expectExceptionMessage('Suppression refusée : vous ne pouvez supprimer que vos propres tâches et celles de l\'utilisateur "Anonymous".');

        $this->client->submit($form);
    }

    public function testDeleteByUserAuthorWithWrongToken(): void
    {
        $this->logInUser();
        $crawler = $this->client->request('GET', '/tasks');
        $form = $crawler->filter('button[id="delete-task-15"]')->form();
        $form['token'] = 'xxxxx';
        $this->client->submit($form);

        $this->assertResponseRedirects('/tasks');
        $this->client->followRedirect();

        $this->assertSelectorExists('.alert.alert-danger');
        $this->assertSelectorTextContains('.alert.alert-danger', 'Oops ! La suppression est refusée. Veuillez vous connecter.');
    }
}
