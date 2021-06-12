<?php

namespace App\Tests\Controller;

use App\Repository\CinemaRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CinemaControllerTest extends WebTestCase
{

    public function testCinemasList(): void
    {
        $client = static::createClient();
        $client->request('GET', '/cinemas');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Dostępne kina');
    }

    public function testCinemaCreateUnauthorized(): void
    {
        $client = static::createClient();
        $client->request('GET', '/cinemas/create');

        $this->assertResponseRedirects();
        $this->assertTrue($client->getResponse()->isRedirect('/login'));
    }

    public function testCinemaUpdateUnauthorized(): void
    {
        $client = static::createClient();
        $cinemaRepository = static::$container->get(CinemaRepository::class);
        $cinemaId = $cinemaRepository->findOneBy(['address' => 'testCinema']);

        $client->request('GET', '/cinemas/'.$cinemaId->getId().'/update');

        $this->assertResponseRedirects();
        $this->assertTrue($client->getResponse()->isRedirect('/login'));
    }

    public function testCinemaHideUnauthorized(): void
    {
        $client = static::createClient();
        $cinemaRepository = static::$container->get(CinemaRepository::class);
        $cinemaId = $cinemaRepository->findOneBy(['address' => 'testCinema']);

        $client->request('GET', '/cinemas/'.$cinemaId->getId().'/hide');

        $this->assertResponseRedirects();
        $this->assertTrue($client->getResponse()->isRedirect('/login'));
    }

    public function testCinemaUnhideUnauthorized(): void
    {
        $client = static::createClient();
        $cinemaRepository = static::$container->get(CinemaRepository::class);
        $cinemaId = $cinemaRepository->findOneBy(['address' => 'testCinema']);

        $client->request('GET', '/cinemas/'.$cinemaId->getId().'/unhide');

        $this->assertResponseRedirects();
        $this->assertTrue($client->getResponse()->isRedirect('/login'));
    }

    public function testCinemaCreateAuthorized(): void
    {
        $client = static::createClient();

        $userRepository = static::$container->get(UserRepository::class);
        $user = $userRepository->findOneBy(['email' => 'admin@example.com']);

        $client->loginUser($user);

        $client->request('GET', '/cinemas/create');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('label', 'Address');
    }

    public function testCinemaUpdateAuthorized(): void
    {
        $client = static::createClient();

        $userRepository = static::$container->get(UserRepository::class);
        $user = $userRepository->findOneBy(['email' => 'admin@example.com']);

        $client->loginUser($user);

        $cinemaRepository = static::$container->get(CinemaRepository::class);
        $cinemaId = $cinemaRepository->findOneBy(['address' => 'testCinema']);

        $client->request('GET', '/cinemas/'.$cinemaId->getId().'/update');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('label', 'Address');
    }

    public function testCinemaHideAuthorized(): void
    {
        $client = static::createClient();

        $userRepository = static::$container->get(UserRepository::class);
        $user = $userRepository->findOneBy(['email' => 'admin@example.com']);

        $client->loginUser($user);

        $cinemaRepository = static::$container->get(CinemaRepository::class);
        $cinemaId = $cinemaRepository->findOneBy(['address' => 'testCinema']);

        $client->request('GET', '/cinemas/'.$cinemaId->getId().'/hide');

        $this->assertResponseRedirects();
        $this->assertTrue($client->getResponse()->isRedirect('/admin'));
    }

    public function testCinemaUnhideAuthorized(): void
    {
        $client = static::createClient();

        $userRepository = static::$container->get(UserRepository::class);
        $user = $userRepository->findOneBy(['email' => 'admin@example.com']);

        $client->loginUser($user);

        $cinemaRepository = static::$container->get(CinemaRepository::class);
        $cinemaId = $cinemaRepository->findOneBy(['address' => 'testCinema']);

        $client->request('GET', '/cinemas/'.$cinemaId->getId().'/unhide');

        $this->assertResponseRedirects();
        $this->assertTrue($client->getResponse()->isRedirect('/admin'));
    }
}
