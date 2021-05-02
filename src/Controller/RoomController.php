<?php

namespace App\Controller;

use App\Entity\Cinema;
use App\Entity\Room;
use App\Entity\Slot;
use App\Form\RoomType;
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
 * @Route("", name="room_")
 */
class RoomController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var MovieDateRepository
     */
    private $movieDateRepository;
    /**
     * @var MovieRepository
     */
    private $movieRepository;

    public function __construct(EntityManagerInterface $entityManager, MovieDateRepository $movieDateRepository, MovieRepository $movieRepository)
    {
        $this->entityManager = $entityManager;
        $this->movieDateRepository = $movieDateRepository;
        $this->movieRepository = $movieRepository;
    }

    /**
     * @Route("/cinemas/{cinemaId}/rooms/create", name="create")
     * @isGranted("ROLE_ADMIN", message="Resource access denied")
     * @ParamConverter(
     *     "cinema",
     *     options={"id"="cinemaId"},
     *     class="App\Entity\Cinema"
     * )
     * @param Request $request
     * @return Response
     */
    public function create(Cinema $cinema, Request $request): Response
    {
        $room = new Room();
        $room->setCinema($cinema);
        $form = $this->createForm(RoomType::class, $room);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $room = $form->getData();
            $this->entityManager->persist($room);
            $this->entityManager->flush();

            for($i=1;$i<25;$i++){
                $slot = new Slot();
                $slot->setRoom($room);
                $slot->setChair($i);
                $this->entityManager->persist($slot);
            }

            $this->entityManager->flush();

            return new RedirectResponse("/cinemas/".$cinema->getId()."/update");
        }

        return $this->render('room/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/cinemas/{cinemaId}/rooms/{roomId}/update", name="update")
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
     */
    public function update(Cinema $cinema, Room $room, Request $request): Response
    {
        $form = $this->createForm(RoomType::class, $room);

        $movieDates = $this->movieDateRepository->findBy(['room' => $room]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $room = $form->getData();
            $this->entityManager->persist($room);
            $this->entityManager->flush();

            return new RedirectResponse("/cinemas/".$cinema->getId()."/update");
        }

        return $this->render('room/update.html.twig', [
            'form' => $form->createView(),
            'cinema' => $cinema,
            'room' => $room,
            'movieDates' => $movieDates
        ]);
    }

    /**
     * @Route("/cinemas/{cinemaId}/rooms/{roomId}/hide", name="hide")
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
     */
    public function hide(Cinema $cinema, Room $room, Request $request)
    {

        $room->setStatus(1);
        $this->entityManager->persist($room);
        $this->entityManager->flush();

        return new RedirectResponse("/cinemas/".$cinema->getId()."/update");

    }

    /**
     * @Route("/cinemas/{cinemaId}/rooms/{roomId}/unhide", name="unhide")
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
     */
    public function unhide(Cinema $cinema, Room $room, Request $request)
    {

        $room->setStatus(0);
        $this->entityManager->persist($room);
        $this->entityManager->flush();

        return new RedirectResponse("/cinemas/".$cinema->getId()."/update");

    }
}
