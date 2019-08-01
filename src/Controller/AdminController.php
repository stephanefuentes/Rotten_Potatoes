<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Repository\MovieRepository;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\MovieType;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Rating;

class AdminController extends AbstractController
{

    /**
     * @Route("/admin/movie", name="admin_movie_list")
     */
    public function movie_list_admin(MovieRepository $repo)
    {

        $movies = $repo->findBy([], ["releasedAt" => "DESC"]);

        return $this->render('admin/movie_list_admin.html.twig', [
            'controller_name' => 'AdminController',
            'movies' => $movies
        ]);
    }


    /**
     * @Route("/admin/movie/delete/{id}", name="admin_movie_delete")
     */
    public function movie_delete_admin(Movie $movie, ObjectManager $manager)
    {

        $manager->remove($movie);
        $manager->flush();

        return $this->redirectToRoute('admin_movie_list');

        // return $this->render('admin/movie_list_admin.html.twig', [
        //     'controller_name' => 'AdminController',
        //     'movies' => $movies
        // ]);
    }


    /**
     * @Route("/admin/movie/edit/{id}", name="admin_movie_edit")
     */
    public function movie_edit_admin(Movie $movie = null, Request $request, ObjectManager $manager)
    {

        if(!$movie)
        {
            $movie = new Movie();
        }

        $form = $this->createForm(MovieType::class, $movie);
        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid() )
        {
            // foreach( $movie->getCategories() as $category)
            // {
            //     $category->addMovie($movie);
            // }

            //dump($form);die();
             $manager->flush();

            return $this->redirectToRoute('admin_movie_list');
        }



        //return $this->redirectToRoute('admin_movie_list');

         return $this->render('admin/movie_edit_admin.html.twig', [
            'controller_name' => 'AdminController',
             'formMovie' => $form->createView()
        ]);
    }



    /**
     * @Route("/admin/rating", name="admin_rating_list")
     */
    public function rating_list_admin(MovieRepository $repo)
    {

        $movies = $repo->findAll();

        //return $this->redirectToRoute('admin_movie_list');

        return $this->render('admin/rating_list_admin.html.twig', [
            'controller_name' => 'AdminController',
            'movies' => $movies
           
        ]);
    }

    /**
     * @Route("/admin/rating/delete/{id}", name="admin_rating_delete")
     */
    public function rating_delete_admin(Rating $rating, ObjectManager $manager)
    {

        $manager->remove($rating);
        $manager->flush();

        return $this->redirectToRoute('admin_rating_list');

        // return $this->render('admin/rating_delete_admin.html.twig', [
        //     'controller_name' => 'AdminController',
        //     'movies' => $movies

        // ]);
    }


}
