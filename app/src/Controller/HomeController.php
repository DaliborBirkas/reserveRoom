<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\Time;
use App\Form\ReservationType;
use App\Service\FindBusyUsersService;
use App\Service\FindMyInvitesService;
use App\Service\MakeReservationService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    #[Route('/home', name: 'app_home')]
    #[IsGranted("ROLE_USER")]
    public function index(Request                $request, FindMyInvitesService $findMyInvitesService,
                          FindBusyUsersService   $findBusyUsersService,
                          MakeReservationService $makeReservationService): Response
    {
        $userCurrent = $this->getUser();
        $reservation = new Reservation();
        $type = ReservationType::class;

        $form = $this->createForm($type, $reservation);
        $form->handleRequest($request);
        $allInvites = $findMyInvitesService->myInvited($userCurrent);

        if ($form->isSubmitted() && $form->isValid()) {
            $intTimeFrom = strtotime($reservation->getTimeFrom());
            $intTimeTo = strtotime($reservation->getTimeTo());
            $busyNames = $findBusyUsersService->findBusyUsers($userCurrent, $intTimeFrom, $intTimeTo, $reservation);
            $makeReservationService->makeReservation($userCurrent, $busyNames, $intTimeFrom, $intTimeTo, $form, $reservation);
        }
        return $this->render('home/home.html.twig', [
            'reservationForm' => $form->createView(),
            'invited' => $allInvites
        ]);
    }

}
