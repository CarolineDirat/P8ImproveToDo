<?php

namespace App\Tests\Controller;

use App\DataFixtures\AppFixtures;
use App\Entity\User;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class TaskControllerTest extends WebTestCase
{
    use FixturesTrait;

    private ?KernelBrowser $client = null;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->loadFixtures([AppFixtures::class]);
    }

    private function logIn(User $user)
    {
        $session = self::$container->get('session');

        $firewallName = 'main';
        // if you don't define multiple connected firewalls, the context defaults to the firewall name
        // See https://symfony.com/doc/current/reference/configuration/security.html#firewall-context
        $firewallContext = 'main';

        // you may need to use a different token class depending on your application.
        // for example, when using Guard authentication you must instantiate PostAuthenticationGuardToken
        $token = new UsernamePasswordToken($user, null, $firewallName, $user->getRoles());
        $session->set('_security_'.$firewallContext, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }

    public function testListDone(): void
    {
        $user = self::$container->get(UserRepository::class)->findOneBy(['username' => 'user1']);

        $this->logIn($user);

        $crawler = $this->client->request('GET', '/tasks/done');

        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertSame('Liste des tâches terminées', $crawler->filter('h1')->text());

    }

    /**
    * @dataProvider provideTaskId
    */
    public function testToggleState(string $id): void
    {
        $user = self::$container->get(UserRepository::class)->findOneBy(['username' => 'user1']);

        $this->logIn($user);
        
        $crawler = $this->client->request('GET', '/tasks');

        $crawler = $this->client->submitForm('toggle-form-'.$id);

        $this->assertResponseRedirects('/tasks');
        $crawler = $this->client->followRedirect();

        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $this->assertSelectorExists('.alert-success');
        $this->assertSame('Liste des tâches', $crawler->filter('h1')->text());
    }
    
    /**
     * provideTaskId
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

        $user = self::$container->get(UserRepository::class)->findOneBy(['username' => 'user1']);
        $this->logIn($user);
        
        $crawler = $this->client->request('GET', '/tasks'.$list);

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
     * provideAjax
     *
     * @return array<array<string>>
     */
    public function provideAjax(): array
    {
        return [
            ['/done', '/tasks/2/toggle-ajax', 2, false],
            //['/waiting', '/tasks/1/toggle-ajax', 1, true],
        ];
    }
}
