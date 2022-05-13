<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\LoginFormType;
use App\Repository\PredictionRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/register", name="user_new", methods={"GET","POST"})
     */
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, ManagerRegistry $doctrine): Response
    {
        $user = new User();
        $form = $this->createForm(LoginFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $hashedPassword = $userPasswordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);

            $entityManager = $doctrine->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            
            return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/profile", name="user_profile", methods={"GET","POST","PATCH"})
     */
    public function profile(
        Request $request,
        PredictionRepository $predictionRepository,
        UserPasswordHasherInterface $userPasswordHasher,
        UserRepository $userRepository,
        ManagerRegistry $doctrine)
    {
        $loggedInUser = $this->getUser();
        

        if ($loggedInUser) {
            $form = $this->createForm(LoginFormType::class, $loggedInUser);
            $form->handleRequest($request);
            $user = $userRepository->findOneBy(['id' => $loggedInUser]);

                if ($form->isSubmitted() && $form->isValid()) {
    
                    if ($form->get('email')->getData() !== $user->getEmail()) {
                        $user->setEmail($form->get('email')->getData());
                    }

                    if ($form->get('personaname')->getData() !== $user->getPersonaname()) {
                        $user->setEmail($form->get('email')->getData());
                    }
    
                    if (!empty($form->get('password')->getData())) {
                        $hashedPassword = $userPasswordHasher->hashPassword($user, $form->get('password')->getData());
                        $user->setPassword($hashedPassword);
                        
                    }
                    $entityManager = $doctrine->getManager();
                    $entityManager->flush();
                    $this->addFlash('success', 'Votre profil a bien été modifié');
                    // return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
                }
            

            $myPredictions = $predictionRepository->findBy(['user' => $user->getId()], ['created_at' => 'DESC', 'updated_at' => 'DESC']);
            dump($myPredictions);
            return $this->renderForm('user/profile.html.twig', ['myPredictions' => $myPredictions, 'user' => $user, 'form' => $form]);
        }

        return $this->redirectToRoute('app_login');
    }
}
