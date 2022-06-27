<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;

class GetNamesMessageService
{
    private EntityManagerInterface $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    public  function  getNames(array $invitedUsers):string{
        $stringNames = '';
        $arrayNamesForMessage = [];
        foreach ($invitedUsers as $value){
            $row = $this->em->getRepository(User::class)->findBy(['id' =>$value]);
            foreach ($row as $value){
                $userNamesForMessage = $value;
                $arrayNamesForMessage[] = $userNamesForMessage->getFirstName();
            }
        }
        foreach ($arrayNamesForMessage as $key=>$values){
            if ($key == 0){
                $stringNames.= $values;
            } else{
                $stringNames.=','.$values;
            }
        }
        return $stringNames;
    }
}