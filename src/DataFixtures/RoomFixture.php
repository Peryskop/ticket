<?php

namespace App\DataFixtures;

use App\Entity\Room;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class RoomFixture extends Fixture implements DependentFixtureInterface
{
    public const ROOM = 'room';

    public function load(ObjectManager $manager)
    {
        $room = new Room();
        $room
            ->setStatus(0)
            ->setRoomNumber(1)
            ->setCinema($this->getReference(CinemaFixture::CINEMA));

        $manager->persist($room);

        $manager->flush();

        $this->addReference(self::ROOM, $room);
    }

    public function getDependencies()
    {
        return [
            CinemaFixture::class
        ];
    }
}
