<?php

namespace App\Controller;

use App\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/users", name="user")
     * @ Security("is_granted('ROLE_USER')")
     * @IsGranted("ROLE_USER")
     */
    public function index(Request $request)
    {
        $userForm = $this->createForm(UserType::class);
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($userForm->getData());
            $entityManager->flush();

            $this->addFlash('success', 'Utilisateur créer');

            return $this->redirectToRoute('homepage');
        }

        return $this->render('user/index.html.twig', [
            'user_form' => $userForm->createView()
        ]);
    }
}
