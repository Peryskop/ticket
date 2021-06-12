<?php

namespace App\Tests\Controller;

use App\Repository\CinemaRepository;
use App\Repository\RoomRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RoomControllerTest extends WebTestCase
{


    public function testRoomCreateUnauthorized(): void
    {
        $client = static::createClient();
        $cinemaRepository = static::$container->get(CinemaRepository::class);
        $cinema = $cinemaRepository->findOneBy(['address' => 'testCinema']);

        $client->request('GET', '/cinemas/'.$cinema->getId().'/rooms/create');

        $this->assertResponseRedirects();
        $this->assertTrue($client->getResponse()->isRedirect('/login'));
    }

    public function testRoomUpdateUnauthorized(): void
    {
        $client = static::createClient();
        $cinemaRepository = static::$container->get(CinemaRepository::class);
        $cinema = $cinemaRepository->findOneBy(['address' => 'testCinema']);

        $roomRepository = static::$container->get(RoomRepository::class);
        $room = $roomRepository->findOneBy(['roomNumber' => 1]);

        $client->request('GET', '/cinemas/'.$cinema->getId().'/rooms/'.$room->getId().'/update');

        $this->assertResponseRedirects();
        $this->assertTrue($client->getResponse()->isRedirect('/login'));
    }

    public function testRoomHideUnauthorized(): void
    {
        $client = static::createClient();
        $cinemaRepository = static::$container->get(CinemaRepository::class);
        $cinema = $cinemaRepository->findOneBy(['address' => 'testCinema']);

        $roomRepository = static::$container->get(RoomRepository::class);
        $room = $roomRepository->findOneBy(['roomNumber' => 1]);

        $client->request('GET', '/cinemas/'.$cinema->getId().'/rooms/'.$room->getId().'/hide');

        $this->assertResponseRedirects();
        $this->assertTrue($client->getResponse()->isRedirect('/login'));
    }

    public function testRoomUnhideUnauthorized(): void
    {
        $client = static::createClient();
        $cinemaRepository = static::$container->get(CinemaRepository::class);
        $cinema = $cinemaRepository->findOneBy(['address' => 'testCinema']);

        $roomRepository = static::$container->get(RoomRepository::class);
        $room = $roomRepository->findOneBy(['roomNumber' => 1]);

        $client->request('GET', '/cinemas/'.$cinema->getId().'/rooms/'.$room->getId().'/unhide');

        $this->assertResponseRedirects();
        $this->assertTrue($client->getResponse()->isRedirect('/login'));
    }

    public function testRoomCreateAuthorized(): void
    {
        $client = static::createClient();

        $userRepository = static::$container->get(UserRepository::class);
        $user = $userRepository->findOneBy(['email' => 'admin@example.com']);

        $client->loginUser($user);

        $cinemaRepository = static::$container->get(CinemaRepository::class);
        $cinema = $cinemaRepository->findOneBy(['address' => 'testCinema']);

        $client->request('GET', '/cinemas/'.$cinema->getId().'/rooms/create');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('label', 'Room number');
    }

    public function testMovieUpdateAuthorized(): void
    {
        $client = static::createClient();

        $userRepository = static::$container->get(UserRepository::class);
        $user = $userRepository->findOneBy(['email' => 'admin@example.com']);

        $client->loginUser($user);

        $cinemaRepository = static::$container->get(CinemaRepository::class);
        $cinema = $cinemaRepository->findOneBy(['address' => 'testCinema']);

        $roomRepository = static::$container->get(RoomRepository::class);
        $room = $roomRepository->findOneBy(['roomNumber' => 1]);

        $client->request('GET', '/cinemas/'.$cinema->getId().'/rooms/'.$room->getId().'/update');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('label', 'Room number');
    }

    public function testMovieHideAuthorized(): void
    {
        $client = static::createClient();

        $userRepository = static::$container->get(UserRepository::class);
        $user = $userRepository->findOneBy(['email' => 'admin@example.com']);

        $client->loginUser($user);

        $cinemaRepository = static::$container->get(CinemaRepository::class);
        $cinema = $cinemaRepository->findOneBy(['address' => 'testCinema']);

        $roomRepository = static::$container->get(RoomRepository::class);
        $room = $roomRepository->findOneBy(['roomNumber' => 1]);

        $client->request('GET', '/cinemas/'.$cinema->getId().'/rooms/'.$room->getId().'/hide');

        $this->assertResponseRedirects();
        $this->assertTrue($client->getResponse()->isRedirect('/cinemas/'.$cinema->getId().'/update'));
    }

    public function testMovieUnhideAuthorized(): void
    {
        $client = static::createClient();

        $userRepository = static::$container->get(UserRepository::class);
        $user = $userRepository->findOneBy(['email' => 'admin@example.com']);

        $client->loginUser($user);

        $cinemaRepository = static::$container->get(CinemaRepository::class);
        $cinema = $cinemaRepository->findOneBy(['address' => 'testCinema']);

        $roomRepository = static::$container->get(RoomRepository::class);
        $room = $roomRepository->findOneBy(['roomNumber' => 1]);

        $client->request('GET', '/cinemas/'.$cinema->getId().'/rooms/'.$room->getId().'/unhide');

        $this->assertResponseRedirects();
        $this->assertTrue($client->getResponse()->isRedirect('/cinemas/'.$cinema->getId().'/update'));
    }
}
