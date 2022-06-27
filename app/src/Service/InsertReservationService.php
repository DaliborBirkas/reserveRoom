<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class InsertReservationService extends AbstractController
{
    private EntityManagerInterface $em;
    private GetInvitedUsersService $getInvitedUsersService;
    private GetNamesMessageService $getNamesMessageService;
    private GetEmailsService $getEmailsService;
    private MailSenderService $mailSenderService;

    public function __construct(EntityManagerInterface $em, GetInvitedUsersService $getInvitedUsersService, GetNamesMessageService $getNamesMessageService,
                                GetEmailsService       $getEmailsService, MailSenderService $mailSenderService)
    {
        $this->em = $em;
        $this->mailSenderService = $mailSenderService;
        $this->getEmailsService = $getEmailsService;
        $this->getNamesMessageService = $getNamesMessageService;
        $this->getInvitedUsersService = $getInvitedUsersService;
    }

    public function insertRow($user, $form, $reservation, array $dataAccepted)
    {

        $invitedUsers = $this->getInvitedUsersService->getInvitedUsers($user, $form);
        $stringNames = $this->getNamesMessageService->getNames($invitedUsers);
        //their mails
        $emails = $this->getEmailsService->getEmails($invitedUsers);
        //send emails
        $this->mailSenderService->sendMail($emails);
        $reservation->setUsers($invitedUsers);
        $reservation->setAccepted($dataAccepted);
        $reservation->setUser($user);
        $this->em->persist($reservation);
        $this->em->flush();
        $this->addFlash('success', 'Reservation created! Invited: ' . $stringNames);
    }


}