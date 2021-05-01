<?php

namespace App\Controller;

use App\Entity\Cinema;
use App\Form\CinemaType;
use App\Repository\CinemaRepository;
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

    public function __construct(EntityManagerInterface $entityManager, CinemaRepository $cinemaRepository)
    {
        $this->entityManager = $entityManager;
        $this->cinemaRepository = $cinemaRepository;
    }

    /**
     * @Route("", name="index")
     */
    public function index(): Response
    {
        $cinemas = $this->cinemaRepository->findAll();
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

            return new RedirectResponse("/cinemas");
        }

        return $this->render('cinema/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="show", requirements={"id"="\d+"})
     */
    public function show(int $id): Response
    {
        $cinema = $this->cinemaRepository->findOneBy(["id" => $id]);
        return $this->render('cinema/show.html.twig', [
            'cinema' => $cinema,
        ]);
    }

    /**
     * @Route("/{id}/update", name="update")
     * @isGranted("ROLE_ADMIN", message="Resource access denied")
     */
    public function update(): Response
    {
        return $this->render('cinema/index.html.twig', [
            'controller_name' => 'CinemaController',
        ]);
    }
}
