<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;

class GetInvitedUsersService
{
    private MailerInterface $mailer;
    private EntityManagerInterface $em;
    public function __construct(MailerInterface $mailer,EntityManagerInterface $em)
    {
        $this->mailer=$mailer;
        $this->em = $em;
    }
    public function getInvitedUsers(User $user, $form):array{
        $data = [];
        $data[] = $user->getId();
        $entityObject = $form->get('users')->getData();
        foreach ($entityObject as $key=>$value){
            $data[] = $value->getId();
        }
        return $data;
    }
}