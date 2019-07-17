<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\ArtistRepository;
use App\Repository\EventRepository;
use App\Repository\JobRepository;
use App\Repository\PictureRepository;
use App\Repository\PriceRepository;
use App\Repository\WebsiteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index(
        ArtistRepository $artistRepository,
        EventRepository $eventRepository,
        PriceRepository $priceRepository,
        WebsiteRepository $websiteRepository,
        JobRepository $jobRepository,
        PictureRepository $pictureRepository
    ) {
        $prices = $priceRepository->findby([], ['value' => 'ASC']);
        $artists = $artistRepository->findall();
        $pictures = $pictureRepository->findall();
        $shows = $jobRepository->findall();
        $website = $websiteRepository->findOneBy(['id' => 1]);
        $events = $eventRepository->findBy([], ['date' => 'ASC']);
        return $this->render('home/index.html.twig', [
            'artists' => $artists,
            'events' => $events,
            'prices' => $prices,
            'website' => $website,
            'shows' => $shows,
            'pictures' => $pictures,
        ]);
    }
}
