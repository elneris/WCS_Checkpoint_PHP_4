<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Form\TicketType;
use App\Repository\TicketRepository;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/ticket")
 */
class TicketController extends AbstractController
{
    /**
     * @Route("/{page}", requirements={"page" = "\d+"}, name="ticket_index", methods={"GET"})
     */
    public function index(int $page, TicketRepository $ticketRepository): Response
    {
        $maxByPage = $this->getParameter('max_page');
        $tickets = $ticketRepository->findAllPaginationAndTrie($page, $maxByPage);

        $pagination = [
            'page' => $page,
            'nbPages' => ceil(count($tickets) / $maxByPage),
            'nameRoute' => 'ticket_index',
            'paramsRoute' => []
        ];
        return $this->render('ticket/index.html.twig', [
            'tickets' => $tickets,
            'pagination' => $pagination
        ]);
    }

    /**
     * @Route("/new", name="ticket_new", methods={"GET","POST"})
     */
    public function new(Request $request, \Swift_Mailer $mailer, \Twig_Environment $twig, TicketRepository $repository): Response
    {
        $ticket = new Ticket();
        $form = $this->createForm(TicketType::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getdata()->getEvent()->getPlace() > 0 ){
                $ticket->getEvent()->setPlace($ticket->getEvent()->getPlace()-1);

                $tickets = $repository->findBy(['event' => $ticket->getEvent()]);

                $ticket->setTicketNumber($ticket->getEvent()->getCity() . (count($tickets)+1));
                $ticket->setValide(false);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($ticket);
                $entityManager->flush();

                $message = (new \Swift_Message('Vos billets'))
                    ->setFrom('wildcircus@gmail.com')
                    ->setTo($ticket->getEmail());

                $message->setBody(
                    $twig->render(
                        'emails/ticket.html.twig',
                        [
                            'ticket' => $ticket,
                            'event' => $ticket->getEvent()
                        ]
                    ),
                    'text/html'
                );
                $mailer->send($message);

                $this->addFlash('success', 'Billet bien acheter, vérifier vos mails !');
            } else {
                $this->addFlash('danger', 'date complete');
            }

        }

        return $this->render('ticket/new.html.twig', [
            'ticket' => $ticket,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/show/{id}", name="ticket_show", methods={"GET"})
     */
    public function show(Ticket $ticket): Response
    {
        return $this->render('ticket/show.html.twig', [
            'ticket' => $ticket,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="ticket_edit", methods={"GET","POST"})
     */
    public function edit(int $id, Request $request, TicketRepository $ticketRepository, EntityManagerInterface $em)
    {
        $ticket = $ticketRepository->findOneBy(['id' => $id]);

        if (!$ticket->getValide()) {
            $ticket->setValide(1);
            $em->flush();
            $this->addFlash('success', 'Billet validé');
        } else {
            $this->addFlash('danger', 'Billet déjà validé');
        }


        return $this->redirectToRoute('admin');
    }

    /**
     * @Route("/{id}", name="ticket_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Ticket $ticket): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ticket->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($ticket);
            $entityManager->flush();
        }

        return $this->redirectToRoute('ticket_index');
    }
}
