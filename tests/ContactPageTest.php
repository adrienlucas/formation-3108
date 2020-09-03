<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\DataCollector\LoggerDataCollector;

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
        $client->enableProfiler();
        $client->submit($contactForm, [
            'contact[email]' => 'mon@ema.il',
            'contact[message]' => 'Hello',
        ]);

        $client->followRedirect();

        static::assertSelectorTextContains('div.success', 'Merci d\'avoir contacté l\'admin');
    }
}
