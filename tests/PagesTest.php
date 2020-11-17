<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @internal
 * @coversNothing
 */
class PagesTest extends WebTestCase
{
    /**
     * testPageIsSuccessFull.
     *
     * @dataProvider provideUrls
     *
     * @param string $url
     */
    public function testPageIsSuccessFull(string $url): void
    {
        $client = self::createClient();
        $client->followRedirects();
        $client->request('GET', $url);

        $this->assertTrue($client->getResponse()->isSuccessful());
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
            ['/tasks/create'],
            ['/users'],
            ['/users/create'],
        ];
    }
}
