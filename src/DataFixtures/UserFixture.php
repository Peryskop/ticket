<?php

namespace App\DataFixtures;

use App\Entity\Room;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends Fixture
{

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $password = $this->encoder->encodePassword($user, 'pass123');
        $user
            ->setEmail('admin@example.com')
            ->setPassword($password)
            ->setRoles(["ROLE_ADMIN"]);

        $manager->persist($user);

        $manager->flush();

    }

}
