<?php

namespace App\MessageHandler;

use App\Entity\User;
use App\Message\CreateUserCommand;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class CreateUserCommandHandler implements MessageHandlerInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $userPasswordEncoder;

    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    public function __construct(
        LoggerInterface $logger,
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $userPasswordEncoder,
        MessageBusInterface $messageBus
    ) {
        $this->logger = $logger;
        $this->entityManager = $entityManager;
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->messageBus = $messageBus;
    }

    public function __invoke(CreateUserCommand $message)
    {
        $this->logger->info(sprintf('Notre message de %s est arrivé et va etre traité..', $message->getContact()));

        $contact = $message->getContact();

        $user = new User();
        $user->setUsername($contact->getEmail());
        $user->setPassword($this->userPasswordEncoder->encodePassword($user, $contact->getEmail()));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->logger->info(sprintf('Utilisateur %s crée !', $message->getContact()));
    }
}
