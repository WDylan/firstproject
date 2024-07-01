<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class LoginControllerTest extends WebTestCase
{
    public function testLoginPageIsAccessible()
    {
        $user = static::createClient();
        $user->request('GET', '/login');

        $this->assertEquals(Response::HTTP_OK, $user->getResponse()->getStatusCode());
    }

    public function testLoginWithBadCredentials()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        // Soumettre le formulaire de connexion avec de mauvaises informations d'identification
        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'utilisateur_inexistant',
            '_password' => 'motdepasse_incorrect',
        ]);
        $client->submit($form);

        // S'attendre à être redirigé vers la même page de connexion
        $this->assertTrue($client->getResponse()->isRedirect('/login'));
        $crawler = $client->followRedirect();

        // Vérifier que le message d'erreur est affiché
        $this->assertNotEmpty($crawler->filter('.alert-danger')->text());
    }

    public function testLogoutRoute()
    {
        $client = static::createClient();
        $client->request('GET', '/deconnexion');

        // La route de déconnexion devrait rediriger ou lancer une exception, selon la configuration de sécurité
        $this->assertTrue($client->getResponse()->isRedirect() || $client->getResponse()->getStatusCode() === Response::HTTP_FORBIDDEN);
    }
}
