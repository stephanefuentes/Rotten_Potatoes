<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use Symfony\Component\HttpFoundation\Request;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;



class SecurityController extends AbstractController
{
    /**
     * @Route("/register", name="security_register")
     */
    public function register(UserPasswordEncoderInterface $encoder, Request $request, ObjectManager $manager)
    {

        $user = new Utilisateur();


        $form = $this->createForm(UtilisateurType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // dump($form->isValid());
            // die();
            $password = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            $manager->persist($user);
            $manager->flush();

            
            return $this->redirectToRoute('security_login');
        }
        

        return $this->render('security/register.html.twig', [
            'controller_name' => 'SecurityController',
            'inscriptionForm' => $form->createView()
        ]);
    }


    /**
     * @Route("/login", name="security_login")
     */
    public function login()
    {
        return $this->render('security/login.html.twig', [
            'controller_name' => 'SecurityController'
            
        ]);
    }


    /**
     * @Route("/logout", name="security_logout")
     */
    public function logout()
    {
        
    }
}
