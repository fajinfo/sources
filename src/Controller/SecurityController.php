<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('@EasyAdmin/page/login.html.twig', [
            'error' => $error,
            'last_username' => $lastUsername,
            'translation_domain' => 'admin',
            'page_title' => 'Suivi des sources',
            'csrf_token_intention' => 'authenticate',
            'target_path' => $this->generateUrl('dashboard'),
            'username_label' => 'Votre nom d\'utilisateur',
            'password_label' => 'Votre mot de passe',
            'sign_in_label' => 'Connexion',
        ]);
    }

    /**
     * @throws \Exception
     * @Route("/logout", name="logout", methods={"GET"})
     */
    public function logout(): void {
        throw new \Exception('This message should not appear');
    }
}
