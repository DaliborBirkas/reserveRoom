<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailSenderService
{
    private MailerInterface $mailer;
    private EntityManagerInterface $em;
    public function __construct(MailerInterface $mailer,EntityManagerInterface $em)
    {
        $this->mailer = $mailer;
        $this->em = $em;
    }

    public function sendMail(array $users)
    {
        foreach ($users as $item){
            $name = $this->em->getRepository(User::class)->findOneBy(['email' =>$item]);
            $email = (new Email())
                ->from('dbirkas38@gmail.com')
                ->to($item)
                ->cc('cc@example.com')
                ->bcc('bcc@example.com')
                ->replyTo('dbirkas38@gmail.com')
                //->priority(Email::PRIORITY_HIGH)
                ->subject('Accept your invite')
                ->text('Sending emails is fun again!')
                ->html('<div> Hello '.$name->getFirstName().'</div><a href="http://localhost:8088/home/">Click on link to accept</a>');
            $this->mailer->send($email);
        }
    }
}