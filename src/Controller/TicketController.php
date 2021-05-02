<?php

namespace App\Controller;

use App\Entity\MovieDate;
use App\Entity\Ticket;
use App\Form\EmployeeTicketType;
use App\Form\TicketType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


class TicketController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/ticket_purchase/{id}", name="ticket_purchase")
     * @ParamConverter(
     *     "movieDate",
     *     class="App\Entity\MovieDate"
     * )
     */
    public function purchase(MovieDate $movieDate, Request $request): Response
    {
        $ticket = new Ticket();
        $ticket->setMovieDate($movieDate);
        if($user = $this->getUser() != null){
            $ticket->setPurchaseType(1);
            $form = $this->createForm(EmployeeTicketType::class, $ticket, ['movieDate' => $movieDate]);
        } else {
            $form = $this->createForm(TicketType::class, $ticket, ['movieDate' => $movieDate]);
        }

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $ticket = $form->getData();
            $this->entityManager->persist($ticket);
            $this->entityManager->flush();

            if($user != null){
                return new RedirectResponse("/print_ticket");
            }

            return new RedirectResponse("/success");
        }

        return $this->render('ticket/purchase.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/success", name="purchase_success")
     */
    public function success(): Response
    {
        return $this->render('ticket/success.html.twig');
    }

    /**
     * @Route("/print_ticket", name="print_ticket")
     */
    public function printTicket(): Response
    {
        return $this->render('ticket/print.html.twig');
    }
}
