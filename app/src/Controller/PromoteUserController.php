<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserTypePromote;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Admin\SetRoleToAdminService;

class PromoteUserController extends AbstractController
{
    private EntityManagerInterface $em;
    private SetRoleToAdminService $setRoleToAdminService;
    public function __construct(EntityManagerInterface $em,SetRoleToAdminService $setRoleToAdminService)
    {
        $this->em = $em;
        $this->setRoleToAdminService=$setRoleToAdminService;
    }
    #[Route('/promote/user/{id}', name: 'app_promote_user')]
    public function index( User $user,Request $request): Response
    {
        $type = UserTypePromote::class;
        $form = $this->createForm($type,$user);
        $roles = $user->getRoles();
        $form->handleRequest($request);
        $this->setRoleToAdminService->setRole($user,$roles);

        return $this->redirectToRoute('app_admin');
    }
}
