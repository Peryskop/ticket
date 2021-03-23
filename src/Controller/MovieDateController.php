<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieDateController extends AbstractController
{
    /**
     * @Route("/movie/date", name="movie_date")
     */
    public function index(): Response
    {
        return $this->render('movie_date/index.html.twig', [
            'controller_name' => 'MovieDateController',
        ]);
    }
}
