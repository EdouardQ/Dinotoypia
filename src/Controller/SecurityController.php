<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/my-account/login', name: 'security.customer.login')]
    public function customerLogin(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser() && in_array("ROLE_CUSTOMER", $this->getUser()->getRoles())) {
            return $this->redirectToRoute('customer.homepage.index');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login_customer.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route('/jurassicback/login', name: 'security.user_back.login')]
    public function userBackLogin(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login_userback.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route('/my-account/logout', name: 'security.customer.logout')]
    public function customerLogout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/jurassicback/logout', name: 'security.user_back.logout')]
    public function userBackLogout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
