<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\Room;
use App\Entity\User;
use App\Form\UserTypePromote;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    #[IsGranted("ROLE_ADMIN")]
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        $roleUser = array();
        $reservation = $this->em->getRepository(Reservation::class)->findAll();
        $rooms = $this->em->getRepository(Room::class)->findAll();
        $users = $this->em->getRepository(User::class)->findAll();
        foreach ($users as $user){
            if (!in_array("ROLE_ADMIN", $user->getRoles())) {
                $roleUser[] = $user;
            }
        }
        return $this->render('admin/home.html.twig', [
            'controller_name' => 'AdminController',
            'reservations'=>$reservation,
            'rooms'=>$rooms,
            'users'=>$roleUser,
        ]);
    }
}
