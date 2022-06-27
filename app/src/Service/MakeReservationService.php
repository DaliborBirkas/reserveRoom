<?php

namespace App\Service;

use App\Entity\Reservation;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MakeReservationService extends AbstractController
{
    private EntityManagerInterface $em;
    private InsertReservationService $insertReservationService;

    public function __construct(EntityManagerInterface $em, InsertReservationService $insertReservationService)
    {
        $this->em = $em;
        $this->insertReservationService = $insertReservationService;
    }

    public function makeReservation(User $user, array $busyNames, int $intTimeFrom, int $intTimeTo, $form,
                                    Reservation $reservation):bool
    {
        $dataAccepted[] = $user->getId();
        $room = $reservation->getRoom();
        $idRoom = $room->getId();
        $capacityRoom = $room->getCapacity();
        $numberofUsers = count($reservation->getUsers()) + 1;
        $this->insert($numberofUsers, $capacityRoom, $busyNames, $idRoom, $reservation,
            $intTimeFrom, $intTimeTo, $user, $form, $dataAccepted);
        return true;
    }

    private function insert(int $numberofUsers, int $capacityRoom, array $busyNames, int $idRoom, Reservation $reservation,
                           int $intTimeFrom, int $intTimeTo, User $user, $form, array$dataAccepted):bool
    {
        if ($this->checkCapacity($numberofUsers, $capacityRoom)) {
            if ($this->findBusyUsers($busyNames)) {

            } else {
                $this->findRoom($idRoom, $reservation, $intTimeFrom, $intTimeTo, $user, $form, $dataAccepted);
            }
        } else {
            $this->addFlash('notice', 'More users than room capacity');
        }
        return true;
    }

    private function findBusyUsers(array $busyNames): bool
    {
        $bool = false;
        if (!empty($busyNames)) {
            $names = '';
            foreach ($busyNames as $key => $values) {
                if ($key == 0) {
                    $names .= $values;
                } else {
                    $names .= ',' . $values;
                }
            }
            $bool = true;
            $this->addFlash('notice12', 'Reservation not created users busy at this time: ' . $names);
        }
        return $bool;
    }

    private function checkCapacity(int $numberofUsers, int $capacityRoom): bool
    {
        $bool = false;
        if ($numberofUsers <= $capacityRoom) {
            $bool = true;
        }
        return $bool;

    }

    private function findRoom(int $idRoom, Reservation $reservation,int $intTimeFrom,int $intTimeTo,User $user,
                              $form,array $dataAccepted):bool
    {
        if ($this->em->getRepository(Reservation::class)->findBy(['room' => $idRoom])) {
            $this->findDate($reservation, $intTimeFrom, $intTimeTo, $user, $form, $dataAccepted);
        } else {
            if ($intTimeFrom < $intTimeTo) {
                $this->insertReservationService->insertRow($user, $form, $reservation, $dataAccepted);
            }
        }
        return true;
    }

    private function findDate(Reservation $reservation,int  $intTimeFrom, int $intTimeTo,User $user, $form,
                              array $dataAccepted):bool
    {
        if ($rows = $this->em->getRepository(Reservation::class)->findBy(['date' => $reservation->getDate()])) {
            $reserve = $rows;
          //  $bool = false;
            $bool = $this->findTimes($reserve, $intTimeFrom, $intTimeTo);

            $this->insertIfTimeNotExists($bool, $intTimeFrom, $intTimeTo, $user, $form, $reservation, $dataAccepted);
        } else {
            if ($intTimeFrom < $intTimeTo) {
                $this->insertReservationService->insertRow($user, $form, $reservation, $dataAccepted);
            }
        }
        return true;
    }

    private function findTimes( array $reserve, int $intTimeFrom, int $intTimeTo):bool
    {
        $bool=false;
        foreach ($reserve as $value) {
            if ($intTimeFrom < strtotime($value->getTimeTo()) && $intTimeTo > strtotime($value->getTimeFrom())) {
                $this->addFlash('notice', 'Can not create reservation at this time! Choose another time.');
                $bool = true;
            }
        }
        return $bool;
    }

    private function insertIfTimeNotExists( bool $bool, int $intTimeFrom, int $intTimeTo,
                                            User $user, $form, Reservation $reservation,array$dataAccepted):bool
    {
        if (!$bool) {
            if ($intTimeFrom < $intTimeTo) {
                $this->insertReservationService->insertRow($user, $form, $reservation, $dataAccepted);
            }
        }
        return true;
    }
}