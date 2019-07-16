<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\ArtistRepository;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index(ArtistRepository $artistRepository, EventRepository $eventRepository)
    {
        $artists = $artistRepository->findall();
        $events = $eventRepository->findall();
        return $this->render('home/index.html.twig', [
            'artists' => $artists,
            'events' => $events
        ]);
    }
}
