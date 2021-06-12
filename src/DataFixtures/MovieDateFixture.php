<?php

namespace App\DataFixtures;

use App\Entity\MovieDate;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MovieDateFixture extends Fixture implements DependentFixtureInterface
{
    public const MOVIEDATE = 'movie-date';

    public function load(ObjectManager $manager)
    {
        $movieDate = new MovieDate();
        $movieDate
            ->setStatus(0)
            ->setRoom($this->getReference(RoomFixture::ROOM))
            ->setMovie($this->getReference(MovieFixture::MOVIE))
            ->setDate(new \DateTime('now'));

        $manager->persist($movieDate);

        $manager->flush();

        $this->addReference(self::MOVIEDATE, $movieDate);
    }

    public function getDependencies()
    {
        return [
            MovieFixture::class,
            RoomFixture::class
        ];
    }
}
