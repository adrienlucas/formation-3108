<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ContactPageTest extends WebTestCase
{
    public function testVisitorCanContactTheAdministrator()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/contact');

        static::assertResponseIsSuccessful();

        static::assertSelectorExists('input[name="contact[email]"]');
        static::assertSelectorExists('textarea[name="contact[message]"]');

        $contactForm = $crawler->selectButton('Envoyer')->form();
        $client->submit($contactForm, [
            'contact[email]' => 'mon@ema.il',
            'contact[message]' => 'Hello',
        ]);

        $client->followRedirect();

        static::assertSelectorTextContains('div.success', 'Merci d\'avoir contacté l\'admin');
    }

    public function testVisitorShouldSubmitValidContactInformations()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/contact');

        static::assertResponseIsSuccessful();

        static::assertSelectorExists('input[name="contact[email]"]');
        static::assertSelectorExists('textarea[name="contact[message]"]');

        $contactForm = $crawler->selectButton('Envoyer')->form();
        $client->submit($contactForm, [
            'contact[email]' => 'toto',
        ]);

        static::assertSelectorTextContains('body', 'Merci de saisir un email valide');
        static::assertSelectorTextContains('body', 'Merci de saisir un message de moins de 120 char');
    }
}
