<?php

namespace App\Service;

use App\Entity\Reservation;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class FindBusyUsersService
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function findBusyUsers(User $user, int $intTimeFrom, int $intTimeTo, Reservation $reservation): array
    {

        $dataCapacityUsers[] = $user->getId();
        foreach ($reservation->getUsers() as $user) {
            $dataCapacityUsers[] = $user->getId();
        }
        $busyNames = [];
        $busy= $this->findDate($reservation,$intTimeFrom,$intTimeTo,$dataCapacityUsers);
        if (!empty($busy)) {
            foreach ($busy as $item) {
                $row = $this->em->getRepository(User::class)->findBy(['id' => $item]);
                $userBusyClass = $row;
                $busyNames[] = $userBusyClass[0]->getFirstName();
            }
        }
        return $busyNames;
    }
    private function findDate(Reservation $reservation,int $intTimeFrom,int $intTimeTo, array $dataCapacityUsers):array{
        $busy=[];
        if ($findUsers = $this->em->getRepository(Reservation::class)->findBy(['date' => $reservation->getDate()])) {
            foreach ($findUsers as $value) {
                $timeFromInt = strtotime($value->getTimeFrom());
                $timeToInt = strtotime($value->getTimeTo());
                if ($intTimeFrom < $timeToInt && $intTimeTo > $timeFromInt) {
                    $busyUsers = $value->getUsers();
                 $busy = $this->getBusyUsers($busyUsers,$dataCapacityUsers);
                }
            }
        }
        return $busy;
    }
    private function getBusyUsers(array $busyUsers,array $dataCapacityUsers):array{
        foreach ($busyUsers as $value) {
            foreach ($dataCapacityUsers as $val) {
                if ($value == $val) {
                    $busy[] = $value;
                }
            }
        }
        return $busy;
    }

}