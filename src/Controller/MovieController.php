<?php

namespace App\Controller;

use App\Repository\MovieRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MovieController extends AbstractController
{
    /**
     * @Route("/", name="movie_list")
     */
    public function movie_list(MovieRepository $repo)
    {
        $movies = $repo->findAll();
dump($movies);
        return $this->render('movie/movie_list.html.twig', [
            'controller_name' => 'MovieController',
            'movies' => $movies
        ]);
    }
}
