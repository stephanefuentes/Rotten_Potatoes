<?php

namespace App\DataFixtures;

use App\Entity\Movie;
use App\Entity\People;
use App\Entity\Rating;
use App\Entity\Category;
use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    /**
     * On aura besoin de l'encoder pour les passwords des utilisateurs
     * 
     * @var UserPasswordEncoderInterface
     */
    private $encoder;
    /**
     * On se fait injecter l'encodeur :-)
     *
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }




    public function load(ObjectManager $manager)
    {

        // pour activer faker (données en français)...vu dans la doc
        $faker = \Faker\Factory::create('fr_FR');
        // $product = new Product();
        // $manager->persist($product);

       
        // Categorie
        $categories = [];
        $categoriesType = ['Horreur', 'Comédie', 'Science Fiction', 'Histoire', 'Action', 'Aventure', 'Animation'];

        foreach($categoriesType as $titre)
        {
            $category = new Category();
            $category->setTitle($titre)
                        ->setSlug($faker->slug);

             $categories[] = $category;

            $manager->persist($category);
        }


        // people
        $peoples = [];
        $pCount =  mt_rand(20, 50);
        for ($p = 0; $p < $pCount; $p++) {
            $people = new People();
            $people->setFirstName($faker->firstName())
                ->setLastName($faker->lastName)
                ->setDescription($faker->realText(60 * mt_rand(8, 20)))
                ->setSlug($faker->slug)
                ->setBirthday($faker->dateTimeBetween("-70 years", "-15 years"))
                ->setPicture("http://placehold.it/400x200");

            // $randomMovies = $faker->randomElements($movies, mt_rand(1, 4));
            // foreach ($randomMovies as $movie) {
            //     $people->addActedIn($movie);
            // }
            $peoples[] = $people;

            $manager->persist($people);
        }


        /**
         * LES UTILISATEURS
         */
        $usersCount = mt_rand(20, 40);
        $users = [];
        for ($u = 0; $u < $usersCount; $u++) {
            $user = new Utilisateur();
            $gender = $faker->randomElement(['men', 'women']);
            $user//->setAvatar($this->getRandomPicture($gender))
                ->setAvatar("http://placehold.it/400x200")
                ->setEmail("user$u@gmail.com")
                ->setPassword($this->encoder->encodePassword($user, "pass"))
                ->setName($faker->userName);
            $manager->persist($user);
            // J'ajoute l'utilisateur au tableau pour m'en resservir après
            $users[] = $user;
        }



       
        // MOVIE
        $mCount =  mt_rand(30, 80);
        for($m = 0; $m < $mCount; $m++ )
        {
            $movie = new Movie();
            $movie->setTitle($faker->catchPhrase)
                ->setSlug($faker->slug)
                //->setPoster($faker->imageUrl(300, 200, null, true))
                ->setPoster($faker->imageUrl(300, 200, null))
                ->setReleasedAt($faker->dateTimeBetween("-40 years"))
                ->setSynopsis($faker->realText(60 * mt_rand(4, 12)));

            // Je prend un people au hasard qui sera le réalisateur
            $director = $faker->randomElement($peoples);
            $movie->setDirector($director);

            // Je prend des peoples au hasard qui seront les acteurs
            $actors = $faker->randomElements($peoples, mt_rand(3, 8));
            foreach ($actors as $actor) {
                $movie->addActor($actor);
            }


            // Je prend des catégories au hasard qui seront les catégories du film
            $randomCategories = $faker->randomElements($categories, mt_rand(1, 3));
            foreach ($randomCategories as $category) {
                $movie->addCategory($category);
            }

           
            /**
             * LES RATINGS D'UN FILM
             */
            $ratingsCount = mt_rand(5, 10);
            for ($r = 0; $r < $ratingsCount; $r++) {
                $rating = new Rating;
                $rating->setComment($faker->realText())
                    ->setCreatedAt($faker->dateTimeBetween("-6 months"))
                    ->setNotation(mt_rand(1, 5))
                    ->setMovie($movie)
                    // Je prend un utilisateur au hasard qui aura posté ce commentaire
                    ->setAuthor($faker->randomElement($users));
                $manager->persist($rating);
            }

            $manager->persist($movie);
        }
     

        $manager->flush();
            
    }

     
    
}
