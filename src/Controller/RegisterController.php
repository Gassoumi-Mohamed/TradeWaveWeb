<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class RegisterController extends AbstractController
{
    #[Route('/register', name: 'register')]
    public function register(Request $request , SessionInterface $session): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Crypter le mot de passe de l'utilisateur

            // Enregistrer l'utilisateur dans la base de données
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // Rediriger l'utilisateur vers une page de confirmation ou de connexion
            $session->set('user', $user);
            return $this->redirectToRoute('verify-email', ['user'=>$user]);
        }

        $this->addFlash('error', 'Le formulaire comporte des erreurs.');

        return $this->render('user/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
