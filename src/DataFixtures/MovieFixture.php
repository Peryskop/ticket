<?php

namespace App\DataFixtures;

use App\Entity\Movie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MovieFixture extends Fixture implements DependentFixtureInterface
{
    public const MOVIE = 'movie';

    public function load(ObjectManager $manager)
    {
         $movie = new Movie();
         $movie
             ->setStatus(0)
             ->setTitle("testMovie")
             ->setCinema($this->getReference(CinemaFixture::CINEMA));

         $manager->persist($movie);

        $manager->flush();

        $this->addReference(self::MOVIE, $movie);
    }

    public function getDependencies()
    {
        return [
            CinemaFixture::class
        ];
    }
}
