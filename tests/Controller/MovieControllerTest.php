<?php

namespace App\Tests\Controller;

use App\Repository\CinemaRepository;
use App\Repository\MovieRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MovieControllerTest extends WebTestCase
{

    public function testMoviesList(): void
    {
        $client = static::createClient();
        $cinemaRepository = static::$container->get(CinemaRepository::class);
        $cinema = $cinemaRepository->findOneBy(['address' => 'testCinema']);

        $client->request('GET', '/cinemas/'.$cinema->getId().'/movies');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Dostępne fimy');
    }

    public function testMovieCreateUnauthorized(): void
    {
        $client = static::createClient();
        $cinemaRepository = static::$container->get(CinemaRepository::class);
        $cinema = $cinemaRepository->findOneBy(['address' => 'testCinema']);

        $client->request('GET', '/cinemas/'.$cinema->getId().'/movies/create');

        $this->assertResponseRedirects();
        $this->assertTrue($client->getResponse()->isRedirect('/login'));
    }

    public function testMovieUpdateUnauthorized(): void
    {
        $client = static::createClient();
        $cinemaRepository = static::$container->get(CinemaRepository::class);
        $cinema = $cinemaRepository->findOneBy(['address' => 'testCinema']);

        $movieRepository = static::$container->get(MovieRepository::class);
        $movie = $movieRepository->findOneBy(['title' => 'testMovie']);

        $client->request('GET', '/cinemas/'.$cinema->getId().'/movies/'.$movie->getId().'/update');

        $this->assertResponseRedirects();
        $this->assertTrue($client->getResponse()->isRedirect('/login'));
    }

    public function testMovieHideUnauthorized(): void
    {
        $client = static::createClient();
        $cinemaRepository = static::$container->get(CinemaRepository::class);
        $cinema = $cinemaRepository->findOneBy(['address' => 'testCinema']);

        $movieRepository = static::$container->get(MovieRepository::class);
        $movie = $movieRepository->findOneBy(['title' => 'testMovie']);

        $client->request('GET', '/cinemas/'.$cinema->getId().'/movies/'.$movie->getId().'/hide');

        $this->assertResponseRedirects();
        $this->assertTrue($client->getResponse()->isRedirect('/login'));
    }

    public function testMovieUnhideUnauthorized(): void
    {
        $client = static::createClient();
        $cinemaRepository = static::$container->get(CinemaRepository::class);
        $cinema = $cinemaRepository->findOneBy(['address' => 'testCinema']);

        $movieRepository = static::$container->get(MovieRepository::class);
        $movie = $movieRepository->findOneBy(['title' => 'testMovie']);

        $client->request('GET', '/cinemas/'.$cinema->getId().'/movies/'.$movie->getId().'/unhide');

        $this->assertResponseRedirects();
        $this->assertTrue($client->getResponse()->isRedirect('/login'));
    }

    public function testMovieCreateAuthorized(): void
    {
        $client = static::createClient();

        $userRepository = static::$container->get(UserRepository::class);
        $user = $userRepository->findOneBy(['email' => 'admin@example.com']);

        $client->loginUser($user);

        $cinemaRepository = static::$container->get(CinemaRepository::class);
        $cinema = $cinemaRepository->findOneBy(['address' => 'testCinema']);

        $client->request('GET', '/cinemas/'.$cinema->getId().'/movies/create');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('label', 'Title');
    }

    public function testMovieUpdateAuthorized(): void
    {
        $client = static::createClient();

        $userRepository = static::$container->get(UserRepository::class);
        $user = $userRepository->findOneBy(['email' => 'admin@example.com']);

        $client->loginUser($user);

        $cinemaRepository = static::$container->get(CinemaRepository::class);
        $cinema = $cinemaRepository->findOneBy(['address' => 'testCinema']);

        $movieRepository = static::$container->get(MovieRepository::class);
        $movie = $movieRepository->findOneBy(['title' => 'testMovie']);

        $client->request('GET', '/cinemas/'.$cinema->getId().'/movies/'.$movie->getId().'/update');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('label', 'Title');
    }

    public function testMovieHideAuthorized(): void
    {
        $client = static::createClient();

        $userRepository = static::$container->get(UserRepository::class);
        $user = $userRepository->findOneBy(['email' => 'admin@example.com']);

        $client->loginUser($user);

        $cinemaRepository = static::$container->get(CinemaRepository::class);
        $cinema = $cinemaRepository->findOneBy(['address' => 'testCinema']);

        $movieRepository = static::$container->get(MovieRepository::class);
        $movie = $movieRepository->findOneBy(['title' => 'testMovie']);

        $client->request('GET', '/cinemas/'.$cinema->getId().'/movies/'.$movie->getId().'/hide');

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

        $movieRepository = static::$container->get(MovieRepository::class);
        $movie = $movieRepository->findOneBy(['title' => 'testMovie']);

        $client->request('GET', '/cinemas/'.$cinema->getId().'/movies/'.$movie->getId().'/unhide');

        $this->assertResponseRedirects();
        $this->assertTrue($client->getResponse()->isRedirect('/cinemas/'.$cinema->getId().'/update'));
    }

}
