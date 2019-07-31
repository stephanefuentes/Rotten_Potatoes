<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\MovieRepository;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Movie;
use Symfony\Component\HttpFoundation\Request;
use App\Form\RatingType;
use App\Entity\Rating;
use Symfony\Component\Security\Core\Security;
use Doctrine\Common\Persistence\ObjectManager;

class MovieController extends AbstractController
{
    /**
     * @Route("/", name="movie_list")
     */
    public function movie_list(MovieRepository $repo)
    {
        //$movies = $repo->findAll();
       $movies = $repo->findBy([], ["releasedAt" => "DESC"]);

        dump($movies);

        return $this->render('movie/movie_list.html.twig', [
            'controller_name' => 'MovieController',
            'movies' => $movies
        ]);
    }



    /**
     * @Route("/category/{slug}", name="movie_list_by_category")
     */
    public function movie_list_by_category(Category $category)
    {
       
        return $this->render('movie/movieCategory_list.html.twig', [
            'controller_name' => 'MovieController',
            'category' => $category
        ]);
    }

    /**
     * @Route("/movie/{slug}", name="movie")
     */
    public function movie(Movie $movie, Request $request, Security $security, ObjectManager $manager)
    {
        $user = $security->getUser();
        $rating = new Rating;

        $form = $this->createForm(RatingType::class, $rating);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {   
           
            $rating->setAuthor($user)
                    ->setMovie($movie);
            // dump($rating);
            // die();
            $manager->persist($rating);
            $manager->flush();

            return $this->redirectToRoute('movie_list');
        }


        return $this->render('movie/movie.html.twig', [
            'controller_name' => 'MovieController',
            'movie' => $movie,
            'formRating' => $form->createView()
        ]);
      
    }


    


// 1 . je veux une fonctin pour afficher un twig qui contiendra les category
// 2 . twig qui bouclera sur les category
// 3. template de base, appel a la fonctio du controller 
// https://symfony.com/doc/current/templating/embedding_controllers.html


}
