<?php

namespace App\Controller;

use App\Form\ContactType;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ContactPageController extends AbstractController
{
    /** @var LoggerInterface */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @Route("/contact", name="contact_page")
     */
    public function index(Request $request)
    {
        $contactForm = $this->createForm(ContactType::class);

        $contactForm->handleRequest($request);

        if ($contactForm->isSubmitted() && $contactForm->isValid()) {
            $this->logger->info('Un nouveau message est arrivé.');

            $this->addFlash('success', 'Merci d\'avoir contacté l\'admin');
            return $this->redirectToRoute('homepage');
        }

        return $this->render('contact_page/index.html.twig', [
            'contact_form' => $contactForm->createView(),
        ]);
    }
}
