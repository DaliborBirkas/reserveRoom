<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils ): Response
    {
        return $this->render('security/login.html.twig',[
            'error'=>$authenticationUtils->getLastAuthenticationError(),
            'last_username'=>$authenticationUtils->getLastUsername()
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout( )
    {
       throw new \Exception('Logout sould not be reached');

    }
}