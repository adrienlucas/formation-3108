<?php

namespace App\Controller;

use App\Form\ContactType;
use App\Message\CreateUserCommand;
use App\Model\Contact;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;


class ContactPageController extends AbstractController
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    public function __construct(LoggerInterface $logger, MessageBusInterface $messageBus)
    {
        $this->logger = $logger;
        $this->messageBus = $messageBus;
    }

    /**
     * @Route("/contact", name="contact_page")
     */
    public function index(Request $request)
    {
        $contact = new Contact();
        $contactForm = $this->createForm(ContactType::class, $contact);

        $contactForm->handleRequest($request);

        if ($contactForm->isSubmitted() && $contactForm->isValid()) {
            $this->logger->info('Un nouveau message est arrivé.');

            $this->messageBus->dispatch(new CreateUserCommand($contact));

            $this->addFlash('success', 'Merci d\'avoir contacté l\'admin');
            return $this->redirectToRoute('homepage');
        }

        return $this->render('contact_page/index.html.twig', [
            'contact_form' => $contactForm->createView(),
        ]);
    }
}
