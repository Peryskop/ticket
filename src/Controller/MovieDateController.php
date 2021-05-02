<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Cinema;
use App\Entity\Movie;
use App\Entity\MovieDate;
use App\Entity\Room;
use App\Form\MovieDateType;
use App\Repository\MovieDateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class MovieDateController extends AbstractController
{
    /**
     * @var MovieDateRepository
     */
    private $movieDateRepository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(MovieDateRepository $movieDateRepository, EntityManagerInterface $entityManager)
    {
        $this->movieDateRepository = $movieDateRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/cinemas/{cinemaId}/rooms/{roomId}/dates/create", name="movie_date_create")
     * @isGranted("ROLE_ADMIN", message="Resource access denied")
     * @ParamConverter(
     *     "cinema",
     *     options={"id"="cinemaId"},
     *     class="App\Entity\Cinema"
     * )
     * @ParamConverter(
     *     "room",
     *     options={"id"="roomId"},
     *     class="App\Entity\Room"
     * )
     * @param Cinema $cinema
     * @param Room $room
     * @param Request $request
     * @return Response
     */
    public function create(Cinema $cinema, Room $room, Request $request): Response
    {
        $movieDate = new MovieDate();
        $movieDate->setRoom($room);

        $form = $this->createForm(MovieDateType::class, $movieDate, ['cinema' => $cinema]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $movieDate = $form->getData();
            $this->entityManager->persist($movieDate);
            $this->entityManager->flush();

            return new RedirectResponse("/cinemas/".$cinema->getId()."/rooms/".$room->getId()."/update");
        }

        return $this->render('movie_date/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @return Response
     * @Route("/cinemas/{cinemaId}/movies/{movieId}/movie_dates", name="movie_dates")
     * @ParamConverter(
     *     "movie",
     *     options={"id"="movieId"},
     *     class="App\Entity\Movie"
     * )
     */
    public function buyTicket(Movie $movie): Response
    {
        return $this->render('movie_date/index.html.twig',[
            'dates' => $this->movieDateRepository->findBy(["movie" => $movie])
        ]);
    }

    /**
     * @Route("/cinemas/{cinemaId}/movies/{movieId}/dates/{movieDateId}/hide", name="hide")
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
     * @ParamConverter(
     *     "movieDate",
     *     options={"id"="movieDateId"},
     *     class="App\Entity\MovieDate"
     * )
     */
    public function hide(Cinema $cinema, Movie $movie, MovieDate $movieDate, Request $request)
    {
        $movieDate->setStatus(1);
        $this->entityManager->persist($movieDate);
        $this->entityManager->flush();

        return new RedirectResponse("/cinemas/".$cinema->getId()."/movies/".$movie->getId()."/update");

    }

    /**
     * @Route("/cinemas/{cinemaId}/movies/{movieId}/dates/{movieDateId}/unhide", name="unhide")
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
     * @ParamConverter(
     *     "movieDate",
     *     options={"id"="movieDateId"},
     *     class="App\Entity\MovieDate"
     * )
     */
    public function unhide(Cinema $cinema, Movie $movie, MovieDate $movieDate, Request $request)
    {
        $movieDate->setStatus(0);
        $this->entityManager->persist($movieDate);
        $this->entityManager->flush();

        return new RedirectResponse("/cinemas/".$cinema->getId()."/movies/".$movie->getId()."/update");

    }
}
