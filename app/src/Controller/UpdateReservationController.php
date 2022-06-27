<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\User;
use App\Form\ReservationTypeUpdate;
use App\Service\Admin\UpdateReservationService;
use App\Service\MailSenderService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class UpdateReservationController extends AbstractController
{
    private EntityManagerInterface $em;
    private MailSenderService $mailSenderService;
    private UpdateReservationService $updateReservationService;

    public function __construct(EntityManagerInterface $em,MailSenderService $mailSenderService,
    UpdateReservationService $updateReservationService)
    {
        $this->em = $em;
        $this->mailSenderService = $mailSenderService;
        $this->updateReservationService = $updateReservationService;
    }
    #[Route('/update/reservation/{id}', name: 'app_update_reservation')]
    public function index(Request $request,Reservation $reservation ): Response
    {
        $type = ReservationTypeUpdate::class;
        $form = $this->createForm($type,$reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() ) {
            $entityObject = $form->get('users')->getData();
            $usersEmail=$this->updateReservationService->updateReservation($entityObject);
            $this->mailSenderService->sendMail($usersEmail);
            $this->updateReservationService->update($reservation,$this->updateReservationService->getInvitedUsers($entityObject));
        }

        return $this->render('update_reservation/home.html.twig', [
            'reservationFormUpdate'=>$form->createView(),
        ]);
    }
}
