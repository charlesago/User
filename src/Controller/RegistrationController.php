<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/registration')]
class RegistrationController extends AbstractController
{
    #[Route('/', name: 'app_registration')]
    public function register(EntityManagerInterface $manager, Request $request, UserPasswordHasherInterface $hasher): Response
    {
        $user = new User();
       $formRegistration = $this->createForm(RegistrationType::class, $user);
       $formRegistration->handleRequest($request);
       if($formRegistration->isSubmitted()&& $formRegistration->isValid()){

           $user->setPassword(
               $hasher->hashPassword(
                   $user,
                   $formRegistration->get('password')->getData()
               )


           );

           $manager->persist($user);
           $manager->flush();

           return $this->redirectToRoute('app_home');
       }

        return $this->renderForm('registration/index.html.twig', [

            'formRegistration'=>$formRegistration
        ]);
    }


}
