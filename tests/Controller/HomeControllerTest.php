<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class HomeControllerTest extends WebTestCase
{
    public function testHomePageIsAccessible()
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testHomePageH1()
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertSelectorTextContains('h1', 'Bienvenue sur FirstProject');
    }

    public function testHomePageBtnRegister()
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertSelectorTextContains('a.btn', "S'inscrire");
    }

    public function testHomePageRedirectToRegister()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $link = $crawler->selectLink("S'inscrire")->link();
        $href = $link->getUri();

        $this->assertEquals('http://localhost/inscription', $href);
    }
}
