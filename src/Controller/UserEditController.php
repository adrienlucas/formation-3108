<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserEditController extends AbstractController
{

    /**
     * @Route("/users/edit/{username}", name="user_edit")
     * @param User $user
     * @param Request $request
     * @ IsGranted("ROLE_USER")
     * @ IsGranted("edit", subject="user")
     * @Security("is_granted('ROLE_USER') and is_granted('edit', user)")
     * @return RedirectResponse|Response
     */
    public function index(User $user, Request $request)
    {
//        $this->denyAccessUnlessGranted('edit', $user);

        $userForm = $this->createForm(UserType::class, $user);

        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($userForm->getData());
            $entityManager->flush();

            $this->addFlash('success', 'Utilisateur créer');

            return $this->redirectToRoute('homepage');
        }
        return $this->render('user_edit/index.html.twig', [
            'form' => $userForm->createView()
        ]);
    }
}
