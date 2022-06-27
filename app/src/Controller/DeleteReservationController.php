<?php

namespace App\Controller;

use App\Entity\Reservation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeleteReservationController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    #[Route('/delete/reservation/{id}', name: 'app_delete_reservation')]
    public function delete(Reservation $reservation): Response
    {
      $this->em->remove($reservation);
      $this->em->flush();
      return $this->redirectToRoute('app_admin');
    }


}
