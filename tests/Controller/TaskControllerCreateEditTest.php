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
class TaskControllerCreateEditTest extends WebTestCase
{
    use FixturesTrait;
    use LoginTrait;

    private KernelBrowser $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->loadFixtures([AppFixtures::class]);
        $this->logInUser();
    }

    public function testCreate(): void
    {
        $crawler = $this->client->request('GET', '/tasks/create');
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertSame('Créer une tâche', $crawler->filter('h1')->text());
    }

    public function testCreateTaskForm(): void
    {
        $crawler = $this->client->request('GET', '/tasks/create');
        $buttonCrawlerNode = $crawler->selectButton('Ajouter');
        $form = $buttonCrawlerNode->form([
            'task[title]' => 'Titre de la tâche',
            'task[content]' => 'Contenu de la tâche',
        ]);
        $this->client->submit($form);
        $this->assertResponseRedirects('/tasks');
        $this->client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');
    }

    public function testEdit(): void
    {
        $crawler = $this->client->request('GET', '/tasks/22/edit');
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertSame('Modifier la tâche "Tâche n°22"', $crawler->filter('h1')->text());
    }

    public function testEditTaskForm(): void
    {
        $crawler = $this->client->request('GET', '/tasks/22/edit');
        $buttonCrawlerNode = $crawler->selectButton('Modifier');
        $form = $buttonCrawlerNode->form([
            'task[title]' => 'Titre modifié de la tâche',
            'task[content]' => 'Contenu modifié de la tâche',
        ]);
        $this->client->submit($form);

        $this->assertResponseRedirects('/tasks');
        $this->client->followRedirect();

        $this->assertSelectorExists('.alert.alert-success');
    }
}
