<?php

namespace App\Controller;

use App\Entity\Cinema;
use App\Entity\Movie;
use App\Entity\Ticket;
use App\Form\TicketType;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

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

    public function __construct(MovieRepository $movieRepository, EntityManagerInterface $entityManager)
    {
        $this->movieRepository = $movieRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/cinemas/{id}/movies", name="movies")
     * @ParamConverter(
     *     "cinema",
     *     class="App\Entity\Cinema"
     * )
     */
    public function index(Cinema $cinema): Response
    {
        return $this->render('movie/index.html.twig', [
            'cinema' => $cinema,
            'movies' => $this->movieRepository->findBy(["cinema" => $cinema])
        ]);
    }

    /**
     * @return Response
     * @Route("/cinemas/{cinemaId}/movies/{movieId}/buy", name="buy_ticket")
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
}
