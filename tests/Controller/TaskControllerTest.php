<?php

namespace App\Tests\Controller;

use App\DataFixtures\AppFixtures;
use App\Repository\TaskRepository;
use App\Tests\LoginTrait;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @internal
 */
class TaskControllerTest extends WebTestCase
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

    public function testListAll(): void
    {
        $crawler = $this->client->request('GET', '/tasks');

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertSame('Liste des tâches', $crawler->filter('h1')->text());
    }

    /**
     * @dataProvider provideIsDone
     */
    public function testList(string $isDone, string $title): void
    {
        $crawler = $this->client->request('GET', '/tasks/filter'.$isDone);

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertSame($title, $crawler->filter('h1')->text());
    }

    /**
     * provideIsDone.
     *
     * @return array<array<string>>
     */
    public function provideIsDone(): array
    {
        return [
            ['/false', 'Liste des tâches non terminées'],
            ['/true', 'Liste des tâches terminées'],
        ];
    }

    /**
     * @dataProvider provideTaskId
     */
    public function testToggleState(string $id): void
    {
        $crawler = $this->client->request('GET', '/tasks');

        $crawler = $this->client->submitForm('toggle-form-'.$id);

        $this->assertResponseRedirects('/tasks');
        $crawler = $this->client->followRedirect();

        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $this->assertSelectorExists('.alert-success');
        $this->assertSame('Liste des tâches', $crawler->filter('h1')->text());
    }

    /**
     * provideTaskId.
     *
     * @return array<array<string>>
     */
    public function provideTaskId(): array
    {
        return [
            ['1'],
            ['2'],
        ];
    }

    /**
     * @dataProvider provideAjax
     */
    public function testToggleStateAjax(string $list, string $uri, int $id, bool $isDone): void
    {
        $crawler = $this->client->request('GET', '/tasks/filter'.$list);

        // checks the task with $id is in the page
        $this->assertSelectorExists('a[href="'.$uri.'"]');

        // prepares the content of AJAX request
        $token = $crawler->filter('a[href="'.$uri.'"]')->attr('data-token');
        $content = self::$container
            ->get('serializer')
            ->serialize(
                ['_token' => $token],
                'json'
            )
        ;

        // send the AJAX Request to TaskController::toggleStateAjax()
        $this->client->xmlHttpRequest('POST', $uri, [], [], [], $content);

        // checks the task has disappeared from the page
        $this->assertSelectorNotExists('a[href="'.$uri.'"]');

        // checks the task's state has been changed
        $task = self::$container->get(TaskRepository::class)->find($id);
        $this->assertEquals($isDone, $task->isDone());
    }

    /**
     * provideAjax.
     *
     * @return array<array<mixed>>
     */
    public function provideAjax(): array
    {
        return [
            ['/true', '/tasks/2/toggle-ajax', 2, false],
            ['/false', '/tasks/1/toggle-ajax', 1, true],
        ];
    }
}
