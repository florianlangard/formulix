<?php

namespace App\Controller;

use DateTime;
use App\Entity\Event;
use App\Entity\Score;
use App\Entity\Prediction;
use App\Form\PredictionFormType;
use App\Form\RacePredictionType;
use App\Service\FormatConverter;
use App\Repository\UserRepository;
use App\Repository\EventRepository;
use App\Repository\ScoreRepository;
use App\Repository\PredictionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PredictionController extends AbstractController
{
    // /**
    //  * @Route("/prediction", name="prediction")
    //  */
    // public function index(): Response
    // {
    //     if ($this->getUser()) {

    //         return $this->render('prediction/index.html.twig', [
    //             'controller_name' => 'PredictionController',
    //         ]);
    //     }
    //     return $this->redirectToRoute('app_login');
    // }

    /**
     * @Route("/prediction/add/qualifying/{slug}", name="prediction_add", methods={"GET", "POST"})
     */
    public function addPrediction(
        Request $request,
        Event $event = null,
        ManagerRegistry $doctrine,
        PredictionRepository $predictionRepository,
        ScoreRepository $scoreRepository,
        FormatConverter $converter
        ): Response
    {
        $user = $this->getUser();
        $currentDate = new DateTime();

        if( $event === null) {
            $this->addFlash('error', 'pas d\'évènement trouvé');
            return $this->redirectToRoute('events_list');
        }
        // dd($currentDate, $event->getDate());
        if ($currentDate > $event->getQualifyingDate()) {
            return $this->redirectToRoute('home', ['error' => 'Trop tard, ça a déjà commencé!']);
        }
        
        if ($user) {

            $rankedUser = $scoreRepository->findOneBy(['user' => $user, 'season' => $event->getSeason()]);
            $existingPrediction = $predictionRepository->findOneBy(['user' => $user, 'event' => $event]);
            
            if (!empty($existingPrediction)) {
                // dd($existingPrediction);
                return $this->redirectToRoute('prediction_edit', ['id' => $existingPrediction->getId()]);
            }

            $prediction = new Prediction();
            
            $form = $this->createForm(PredictionFormType::class, $prediction);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                // get identified User
                // $user = $this->getUser();
                $currentDate = new DateTime();
                if ($currentDate > $event->getQualifyingDate()) {
                    return $this->redirectToRoute('home', ['error' => 'Trop tard, ça a déjà commencé!']);
                }

                // Relations with prediction 
                $prediction->setUser($user);
                $prediction->setEvent($event);

                $pole = $form->get('pole')->getData();
                $prediction->setPole($pole);

                $time = $converter->formatTimeString(
                    $form->get('min')->getData(),
                    $form->get('sec')->getData(),
                    $form->get('msec')->getData()
                );
                $prediction->setTime($time);

                if ($rankedUser === null){
                    $score = new Score();
                    $score->setUser($user);
                    $score->setSeason($event->getSeason());
                    $score->setTotal(0);

                    $entityManager = $doctrine->getManager();
                    
                    $entityManager->persist($score);
                }
                
                $entityManager = $doctrine->getManager();
                $entityManager->persist($prediction);
                $entityManager->flush();
                
                $this->addFlash('success', 'prono placé, Bonne chance!');
                
                return $this->redirectToRoute('home');
            }
            
            return $this->render('prediction/index.html.twig', ['form' => $form->createView(), 'event' => $event]);
        }

        return $this->redirectToRoute('app_login');
    }

    /**
     * @Route("/prediction/add/race/{slug}", name="race_prediction_add", methods={"GET", "POST"})
     */
    public function addRacePrediction(
        Request $request,
        Event $event = null,
        ManagerRegistry $doctrine,
        PredictionRepository $predictionRepository,
        ScoreRepository $scoreRepository,
        FormatConverter $converter
        ): Response
    {
        $user = $this->getUser();
        $currentDate = new DateTime();

        if( $event === null) {
            $this->addFlash('error', 'pas d\'évènement trouvé');
            return $this->redirectToRoute('events_list');
        }
        // dd($currentDate, $event->getDate());
        if ($currentDate > $event->getQualifyingDate()) {
            return $this->redirectToRoute('home', ['error' => 'Trop tard, ça a déjà commencé!']);
        }
        
        if ($user) {

            $rankedUser = $scoreRepository->findOneBy(['user' => $user, 'season' => $event->getSeason()]);
            $existingPrediction = $predictionRepository->findOneBy(['user' => $user, 'event' => $event]);
            
            if (!empty($existingPrediction)) {
                return $this->redirectToRoute('prediction_edit', ['id' => $existingPrediction->getId()]);
            }

            $prediction = new Prediction();
            
            $form = $this->createForm(RacePredictionType::class, $prediction);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                // get identified User
                // $user = $this->getUser();
                $currentDate = new DateTime();
                if ($currentDate > $event->getQualifyingDate()) {
                    return $this->redirectToRoute('home', ['error' => 'Trop tard, ça a déjà commencé!']);
                }

                // Relations with prediction 
                $prediction->setUser($user);
                $prediction->setEvent($event);

                $pole = $form->get('pole')->getData();
                $prediction->setPole($pole);

                $time = $converter->formatTimeString(
                    $form->get('min')->getData(),
                    $form->get('sec')->getData(),
                    $form->get('msec')->getData()
                );
                $prediction->setTime($time);

                if ($rankedUser === null){
                    $score = new Score();
                    $score->setUser($user);
                    $score->setSeason($event->getSeason());
                    $score->setTotal(0);

                    $entityManager = $doctrine->getManager();
                    
                    $entityManager->persist($score);
                }
                
                $entityManager = $doctrine->getManager();
                $entityManager->persist($prediction);
                $entityManager->flush();
                
                $this->addFlash('success', 'prono placé, Bonne chance!');
                
                return $this->redirectToRoute('home');
            }
            
            return $this->render('prediction/index.html.twig', ['form' => $form->createView(), 'event' => $event]);
        }

        return $this->redirectToRoute('app_login');
    }

    /**
     * @Route("/prediction/edit/{id}", name="prediction_edit", methods={"GET", "POST", "PATCH", "PUT"})
     */
    public function editPrediction(Request $request, Prediction $prediction = null, PredictionRepository $predictionRepository, ManagerRegistry $doctrine, FormatConverter $converter): Response
    {
        if( $prediction === null) {
            $this->addFlash('error', 'pas d\'évènement trouvé');
            return $this->redirectToRoute('events_list');
        }
        // if ($this->getUser()) {
            $form = $this->createForm(PredictionFormType::class, $prediction);

            $form->handleRequest($request);
            $event = $prediction->getEvent();

            if ($form->isSubmitted() && $form->isValid()) {
                // get identified User
                $user = $this->getUser();
                // Relations with prediction 
                $prediction->setUser($user);

                $pole = $form->get('pole')->getData();
                $prediction->setPole($pole);

                $time = $converter->formatTimeString(
                    $form->get('min')->getData(),
                    $form->get('sec')->getData(),
                    $form->get('msec')->getData()
                );
                $prediction->setTime($time);

                $prediction->setUpdatedAt(new DateTime());

                $entityManager = $doctrine->getManager();
                $entityManager->persist($prediction);
                $entityManager->flush();

                $this->addFlash('success', 'prono modifié, Bonne chance!');
                return $this->redirectToRoute('home');
            }

            return $this->render('prediction/index.html.twig', ['form' => $form->createView(), 'event' => $event]);
        // }
        // return $this->redirectToRoute('login');
    }
}
