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
        $client = self::createClient(/* @todo auth with ROLE_USER */);
        $client->request('GET', '/users');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('input[name="user[username]"]');
        $this->assertSelectorExists('input[name="user[password]"]');
        $this->assertSelectorNotExists('input[name="user[is_admin]"]');

        $client->request('POST', '/users', [
            'user[username]' => 'toto',
            'user[password]' => 'toto',
            'user[is_admin]' => true
        ]);

        $this->assertSame(Response::HTTP_FORBIDDEN, $client->getResponse()->getStatusCode());
    }
}
