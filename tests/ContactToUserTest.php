<?php

namespace App\Tests;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\DataCollector\LoggerDataCollector;
use Symfony\Component\Process\Process;

class ContactToUserTest extends WebTestCase
{
    public function testTheAppCanCreateAnUserFromAContactRequest()
    {
        $client = static::createClient();

        $entityManager = self::$container->get(EntityManagerInterface::class);

        // Empty the database tables user AND messenger_messages before the test
        $entityManager
            ->getConnection()
            ->exec('DELETE FROM messenger_messages; DELETE FROM user');

        $crawler = $client->request('GET', '/contact');

        static::assertResponseIsSuccessful();

        $expectedUsername = 'user@example.com';
        $contactForm = $crawler->selectButton('Envoyer')->form();
        $client->submit($contactForm, [
            'contact[email]' => $expectedUsername,
            'contact[message]' => 'Hello, peux-tu me créer un compte stp ?',
        ]);

        static::assertResponseRedirects();

        $repository = $entityManager->getRepository(User::class);

        sleep(0.5);
        $users = $repository->findAll();
        $this->assertCount(0, $users);

        sleep(0.5);
        $process = Process::fromShellCommandline('php bin/console messenger:consume -l 1 -t 2');
        $process->run();

        $users = $repository->findAll();

        $this->assertCount(1, $users);
        $this->assertSame($expectedUsername, $users[0]->getUsername());
    }
}
