<?php

namespace App\Service;

use App\Entity\Reservation;
use App\Entity\Room;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;

class FindMyInvitesService
{
    private MailerInterface $mailer;
    private EntityManagerInterface $em;
    public function __construct(MailerInterface $mailer,EntityManagerInterface $em)
    {
        $this->mailer = $mailer;
        $this->em = $em;
    }
    public function myInvited(User $user):array{
        $invites=[];
        if($row=$this->em->getRepository(Reservation::class)->findAll()){
            foreach ($row as $value){
                $yourInvites[] = $value->getUsers();
                $acceptedInvites = $value->getAccepted();
                $found=$this->userAcceptedInvite($acceptedInvites,$user);

                 $allInvites= $this->userPendingInvite($found,$yourInvites,$user,$value);
                $invites[]=$allInvites;
            }

        }
        return $invites;

    }
    private function userAcceptedInvite(array $acceptedInvites,User $user):bool{
        $found=false;
        foreach ($acceptedInvites as $acceptedInvite){
            $userId=$user->getId();
            $userId = intval($userId);
            if ($userId == $acceptedInvite){
                $found=true;
            }
        }
        return $found;
    }
    private function userPendingInvite(bool $found,array $yourInvites,User $user,$value):array{
        $allInvites=[];
        if (!$found){
            foreach ($yourInvites as $invite){
                foreach ($invite as $item){
                    $id = intval($item);
                    $userId = $user->getId();
                    $userId = intval($userId);
                   $allInvites= $this->fillData($userId,$id,$value);
                }
            }
        }

        return $allInvites;

    }
    private function  fillData(int $userId,int $id, $value):array{
       $allInvites=[];
        if($userId == $id){
            if (strtotime(date('Y-m-d')) > strtotime($value->getDate()->format('Y-m-d')) ){
            } else{
                $invited = [];
                $invited[] = $value->getId();
                $invited[] = $value->getTimeFrom();
                $invited[] = $value->getTimeTo();
                $invited[] = $value->getDate();

                $invited[] = $userId;

                $reservedRoom = $value->getRoom();
                $roomGet = $this->em->getRepository(Room::class)->findOneBy(['id'=>$reservedRoom]);
                $roomName = $roomGet->getName();

                $invited[] = $roomName;

                $firstName = $value->getUser();
                $userGet = $this->em->getRepository(User::class)->findOneBy(['id'=>$firstName]);
                $userName = $userGet->getFirstName();

                $invited[] = $userName;
                $allInvites[] = $invited;

            }


        }
     //  var_dump($allInvites);
        return $allInvites ;
    }
}