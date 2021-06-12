<?php

namespace App\DataFixtures;

use App\Entity\Cinema;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CinemaFixture extends Fixture
{
    public const CINEMA = 'cinema';

    public function load(ObjectManager $manager)
    {
         $cinema = new Cinema();
         $cinema
             ->setAddress("testCinema")
             ->setStatus(0);

         $manager->persist($cinema);


        $manager->flush();

        $this->addReference(self::CINEMA, $cinema);

    }
}
