<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;

class GetEmailsService
{
    private MailerInterface $mailer;
    private EntityManagerInterface $em;
    public function __construct(MailerInterface $mailer,EntityManagerInterface $em)
    {
        $this->mailer = $mailer;
        $this->em = $em;
    }

  public function  getEmails(array $data):array{
      foreach ($data as $value){
          $user = $this->em->getRepository(User::class)->findOneBy(['id' =>$value]);
          $usersEmail[] = $user->getUserIdentifier();
      }
      return $usersEmail;
  }

}