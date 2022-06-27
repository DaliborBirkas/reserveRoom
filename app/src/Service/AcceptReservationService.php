<?php

namespace App\Service;

use App\Entity\Reservation;
use Doctrine\ORM\EntityManagerInterface;

class AcceptReservationService
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    public function acceptReservation($idReservation,$idUser):bool{
        if($reservation = $this->em->getRepository(Reservation::class)->findOneBy(['id'=>$idReservation])){
            $busy[] = $reservation->getAccepted();
            $allData = [];
            $data = [];
            $data[] = $idUser;
            if (empty($busy[0])) {
                $reservation->setAccepted($data);
                $this->em->persist($reservation);
                $this->em->flush();
            } else {
                foreach ($busy as $value) {
                    foreach ($value as $item) {
                        $allData[] = $item;
                    }
                }
                $allData[] = $idUser;
                $reservation->setAccepted($allData);
                $this->em->persist($reservation);
                $this->em->flush();
            }
        }
        return true;
    }
}