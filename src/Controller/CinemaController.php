<?php

namespace App\Controller;

use App\Entity\Cinema;
use App\Form\CinemaType;
use App\Repository\CinemaRepository;
use App\Repository\MovieRepository;
use App\Repository\RoomRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/cinemas", name="cinema_")
 */
class CinemaController extends AbstractFOSRestController
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var CinemaRepository
     */
    private $cinemaRepository;
    /**
     * @var MovieRepository
     */
    private $movieRepository;
    /**
     * @var RoomRepository
     */
    private $roomRepository;

    public function __construct(EntityManagerInterface $entityManager, CinemaRepository $cinemaRepository, MovieRepository $movieRepository, RoomRepository $roomRepository)
    {
        $this->entityManager = $entityManager;
        $this->cinemaRepository = $cinemaRepository;
        $this->movieRepository = $movieRepository;
        $this->roomRepository = $roomRepository;
    }

    /**
     * @Route("", name="index")
     */
    public function index(): Response
    {
        $cinemas = $this->cinemaRepository->findBy(["status" => 0]);
        return $this->render('cinema/index.html.twig', [
            'cinemas' => $cinemas,
        ]);
    }

    /**
     * @Route("/create", name="create")
     * @isGranted("ROLE_ADMIN", message="Resource access denied")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request): Response
    {
        $cinema = new Cinema();
        $form = $this->createForm(CinemaType::class, $cinema);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $cinema = $form->getData();
            $this->entityManager->persist($cinema);
            $this->entityManager->flush();

            return new RedirectResponse("/admin");
        }

        return $this->render('cinema/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/update", name="update")
     * @isGranted("ROLE_ADMIN", message="Resource access denied")
     */
    public function update(Cinema $cinema, Request $request): Response
    {
        $form = $this->createForm(CinemaType::class, $cinema);

        $movies = $this->movieRepository->findBy(['cinema' => $cinema]);
        $rooms = $this->roomRepository->findBy(['cinema' => $cinema]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $cinema = $form->getData();
            $this->entityManager->persist($cinema);
            $this->entityManager->flush();

            return new RedirectResponse("/admin");
        }

        return $this->render('cinema/update.html.twig', [
            'form' => $form->createView(),
            'movies' => $movies,
            'cinema' => $cinema,
            'rooms' => $rooms
        ]);
    }

    /**
     * @Route("/{id}/hide", name="hide")
     * @isGranted("ROLE_ADMIN", message="Resource access denied")
     */
    public function hide(Cinema $cinema, Request $request)
    {

        $cinema->setStatus(1);
        $this->entityManager->persist($cinema);
        $this->entityManager->flush();

        return new RedirectResponse("/admin");

    }

    /**
     * @Route("/{id}/unhide", name="unhide")
     * @isGranted("ROLE_ADMIN", message="Resource access denied")
     */
    public function unhide(Cinema $cinema, Request $request)
    {

        $cinema->setStatus(0);
        $this->entityManager->persist($cinema);
        $this->entityManager->flush();

        return new RedirectResponse("/admin");

    }
}
