<?php

declare(strict_types=1);

namespace App\Tests;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class UserCreationTest extends WebTestCase
{
    public function testAccessIsDeniedWhenNotAuthenticated()
    {
        $client = self::createClient();
        $client->request('GET', '/users');

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testAdminUsersCanCreateNewAdmin()
    {
        $client = self::createClient([], [
            'PHP_AUTH_USER' => 'john',
            'PHP_AUTH_PW'   => 'j0hn',
        ]);

        $client->request('GET', '/users');

        $this->assertResponseIsSuccessful();

        $client->submitForm('Créer', [
            'user[username]' => 'toto',
            'user[password]' => 'toto',
            'user[admin]' => true
        ]);

        $client->followRedirect();
        $this->assertSelectorTextContains('div.success', 'Utilisateur créer');
    }

    public function testNonAdminUsersCanNotCreateAdmin()
    {
        $client = self::createClient([], [
          'PHP_AUTH_USER' => 'adrien',
          'PHP_AUTH_PW'   => '4dr13n',
        ]);
        $client->request('GET', '/users');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('input[name="user[username]"]');
        $this->assertSelectorExists('input[name="user[password]"]');
        $this->assertSelectorNotExists('input[name="user[admin]"]');

        $client->request('POST', '/users', [
            'user' => [
              'username' => 'toto',
              'password' => 'toto',
              'admin' => true,
            ]
        ]);

        $this->assertSelectorTextContains('form', 'This form should not contain extra fields');
    }
}
