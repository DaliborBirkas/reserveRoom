<?php

namespace App\Service\Admin;
use Doctrine\ORM\EntityManagerInterface;
use \Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\User;
class SetRoleToAdminService extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    public function setRole(User $user,array $roles){
        if (!in_array("ROLE_ADMIN", $roles)) {
            $roles[] = "ROLE_ADMIN";
            $user->setRoles($roles);
            $this->em->persist($user);
            $this->em->flush();
            $this->addFlash('success', 'User promoted to admin');
        }
    }

}