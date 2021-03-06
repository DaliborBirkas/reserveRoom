<?php

namespace App\DataFixtures;

use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        //$manager->persist($product);
        UserFactory::createOne([
            'email'=>'pericadmin@gmail.com',
            'roles'=>['ROLE_ADMIN']
        ]);
        UserFactory::createOne([
            'email'=>'pericauser@gmail.com',
        ]);
        UserFactory::createMany(10);
    }


}