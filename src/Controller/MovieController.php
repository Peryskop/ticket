<?php

namespace App\Controller;

use App\Entity\Cinema;
use App\Entity\Movie;
use App\Entity\Ticket;
use App\Form\MovieType;
use App\Form\TicketType;
use App\Repository\MovieDateRepository;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
 * @Route("/cinemas", name="movie_")
 */
class MovieController extends AbstractController
{

    /**
     * @var MovieRepository
     */
    private $movieRepository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var MovieDateRepository
     */
    private $movieDateRepository;

    public function __construct(MovieRepository $movieRepository, EntityManagerInterface $entityManager, MovieDateRepository $movieDateRepository)
    {
        $this->movieRepository = $movieRepository;
        $this->entityManager = $entityManager;
        $this->movieDateRepository = $movieDateRepository;
    }

    /**
     * @Route("/{id}/movies", name="movies")
     * @ParamConverter(
     *     "cinema",
     *     class="App\Entity\Cinema"
     * )
     */
    public function index(Cinema $cinema): Response
    {
        return $this->render('movie/index.html.twig', [
            'cinema' => $cinema,
            'movies' => $this->movieRepository->findBy(["cinema" => $cinema, "status" => 0])
        ]);
    }

    /**
     * @return Response
     * @Route("/{cinemaId}/movies/{movieId}/buy", name="buy_ticket")
     * @ParamConverter(
     *     "movie",
     *     options={"id"="movieId"},
     *     class="App\Entity\Movie"
     * )
     */
    public function buyTicket(Movie $movie, Request $request): Response
    {

        $ticket = new Ticket();

        $form = $this->createForm(TicketType::class, $ticket, ['movie' => $movie]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $cinema = $form->getData();
            $this->entityManager->persist($cinema);
            $this->entityManager->flush();

            return new RedirectResponse("/success");
        }

        return $this->render('movie/buyTicket.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/movies/create", name="movie_create")
     * @isGranted("ROLE_ADMIN", message="Resource access denied")
     * @ParamConverter(
     *     "cinema",
     *     class="App\Entity\Cinema"
     * )
     * @param Request $request
     * @return Response
     */
    public function create(Cinema $cinema, Request $request): Response
    {
        $movie = new Movie();
        $movie->setCinema($cinema);
        $form = $this->createForm(MovieType::class, $movie);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $movie = $form->getData();
            $this->entityManager->persist($movie);
            $this->entityManager->flush();

            return new RedirectResponse("/cinemas/".$cinema->getId()."/update");
        }

        return $this->render('cinema/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{cinemaId}/movies/{movieId}/update", name="update")
     * @isGranted("ROLE_ADMIN", message="Resource access denied")
     * @ParamConverter(
     *     "cinema",
     *     options={"id"="cinemaId"},
     *     class="App\Entity\Cinema"
     * )
     * @ParamConverter(
     *     "movie",
     *     options={"id"="movieId"},
     *     class="App\Entity\Movie"
     * )
     */
    public function update(Cinema $cinema, Movie $movie, Request $request): Response
    {
        $form = $this->createForm(MovieType::class, $movie);

        $movieDates = $this->movieDateRepository->findBy(['movie' => $movie]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $movie = $form->getData();
            $this->entityManager->persist($movie);
            $this->entityManager->flush();

            return new RedirectResponse("/cinemas/".$cinema->getId()."/update");
        }

        return $this->render('movie/update.html.twig', [
            'form' => $form->createView(),
            'cinema' => $cinema,
            'movie' => $movie,
            'movieDates' => $movieDates
        ]);
    }

    /**
     * @Route("/{cinemaId}/movies/{movieId}/hide", name="hide")
     * @isGranted("ROLE_ADMIN", message="Resource access denied")
     * @ParamConverter(
     *     "cinema",
     *     options={"id"="cinemaId"},
     *     class="App\Entity\Cinema"
     * )
     * @ParamConverter(
     *     "movie",
     *     options={"id"="movieId"},
     *     class="App\Entity\Movie"
     * )
     */
    public function hide(Cinema $cinema, Movie $movie, Request $request)
    {
        $movie->setStatus(1);
        $this->entityManager->persist($movie);
        $this->entityManager->flush();

        return new RedirectResponse("/cinemas/".$cinema->getId()."/update");

    }

    /**
     * @Route("/{cinemaId}/movies/{movieId}/unhide", name="unhide")
     * @isGranted("ROLE_ADMIN", message="Resource access denied")
     * @ParamConverter(
     *     "cinema",
     *     options={"id"="cinemaId"},
     *     class="App\Entity\Cinema"
     * )
     * @ParamConverter(
     *     "movie",
     *     options={"id"="movieId"},
     *     class="App\Entity\Movie"
     * )
     */
    public function unhide(Cinema $cinema, Movie $movie, Request $request)
    {
        $movie->setStatus(0);
        $this->entityManager->persist($movie);
        $this->entityManager->flush();

        return new RedirectResponse("/cinemas/".$cinema->getId()."/update");

    }
}
