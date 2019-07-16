<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Form\SearchTicketType;
use App\Repository\TicketRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(Request $request, TicketRepository $ticketRepository)
    {
        $form = $this->createForm(SearchTicketType::class);
        $tikets = [];

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tikets = $ticketRepository->myFilter($form->getData());
        }

        return $this->render('admin/index.html.twig', [
            'form' => $form->createView(),
            'tickets' => $tikets
        ]);
    }
}
