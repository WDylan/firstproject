<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class RegisterControllerTest extends WebTestCase
{
    public function testRegisterPageIsAccessible()
    {
        $client = static::createClient();
        $client->request('GET', '/inscription');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testRegisterPageH1()
    {
        $client = static::createClient();
        $client->request('GET', '/inscription');

        $this->assertSelectorTextContains('h1', 'Formulaire d\'inscription');
    }

    public function testRegisterPageValidRegistrationForm()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/inscription');

        $form = $crawler->selectButton('Enregistrer')->form([
            'registration[email]' => 'user@user.com',
            'registration[plainPassword][first]' => 'password',
            'registration[plainPassword][second]' => 'password',
            'registration[name]' => 'User User',
            'registration[pseudo]' => 'userPseudo',
        ]);
        $client->submit($form);
        $this->assertResponseRedirects();

        $client->followRedirect();
        $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Se connecter');
    }
    
}
