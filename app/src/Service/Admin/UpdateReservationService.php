<?php

namespace App\Service\Admin;

use App\Entity\Reservation;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UpdateReservationService
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    public function updateReservation($entityObject):array{
        $invitedUsers = $this->getInvitedUsers($entityObject);
        foreach ($invitedUsers as $value){
            $userId = $this->em->getRepository(User::class)->findOneBy(['id' =>$value]);
            $usersEmail[] = $userId->getUserIdentifier();
        }
        return $usersEmail;
    }
    public function getInvitedUsers($entityObject):array{
        $invitedUsers = [];
        foreach ($entityObject as $key=>$value){
            $invitedUsers[] = $value->getId();
        }
        return $invitedUsers;
    }
    public function update(Reservation $reservation, $invitedUsers){
        $accepted = [];
        $reservation->setUsers($invitedUsers);
        $reservation->setAccepted($accepted);
        $this->em->persist($reservation);
        $this->em->flush();
    }
}