<?php

namespace App\Controller;

use App\Repository\EventRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends AbstractController
{
    /**
     * @Route("/events", name="events_list")
     */
    public function list(EventRepository $eventRepository): Response
    {
        $date = new DateTime();
        $events = $eventRepository->findBy([], ['date' => 'ASC']);
        return $this->render('event/index.html.twig', [
            'events' => $events,
            'date' => $date,
        ]);
    }
}
