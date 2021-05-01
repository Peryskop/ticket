<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Repository\MovieDateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


class MovieDateController extends AbstractController
{
    /**
     * @var MovieDateRepository
     */
    private $movieDateRepository;

    public function __construct(MovieDateRepository $movieDateRepository)
    {
        $this->movieDateRepository = $movieDateRepository;
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
        $dates = $this->movieDateRepository->findBy(["movie" => $movie]);

        return $this->render('movie_date/index.html.twig',[
            'dates' => $dates
        ]);
    }
}
