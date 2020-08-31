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

        /** @var LoggerDataCollector $collector */
//        $collector = $client->getProfile()->getCollector('logger');
//        static::assertSame('Un nouveau message est arrivé.', $collector->getLogs()['info'][0]);

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
            'contact[message]' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris tincidunt, urna at iaculis egestas, dolor lectus imperdiet leo, vel cursus urna tellus in neque. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin eget congue orci. Pellentesque eu velit neque. Vivamus gravida sem placerat lectus vulputate bibendum. Proin viverra, dolor sed ultrices viverra, magna libero suscipit augue, maximus luctus eros ipsum id augue. Duis scelerisque ullamcorper enim, eget eleifend mi tincidunt id. Maecenas cursus tristique nibh eu ultricies.'
        ]);
//        dump($client->getCrawler()->html());

        static::assertSelectorTextContains('form[name="contact"]', 'Merci de saisir un email valide');
        static::assertSelectorTextContains('form[name="contact"]', 'Merci de saisir un message de moins de 120 char');
    }
}
