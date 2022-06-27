<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Service\AcceptReservationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class AcceptController extends AbstractController
{
    private EntityManagerInterface $em;
    private AcceptReservationService $acceptReservationService;

    public function __construct(EntityManagerInterface $em,AcceptReservationService $acceptReservationService)
    {
        $this->em = $em;
        $this->acceptReservationService = $acceptReservationService;
    }
    #[Route('/accept/{idReservation}/{idUser}', name: 'app_accept')]
    public function index(Request $request): Response
    {
        $idReservation = $request->attributes->get('idReservation');
        $idUser=$request->attributes->get('idUser');
        $idUser = intval($idUser);
        $this->acceptReservationService->acceptReservation($idReservation,$idUser);

        return $this->redirectToRoute('app_home');
    }
}
