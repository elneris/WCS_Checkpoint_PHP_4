<?php

namespace App\Controller;

use App\Entity\Website;
use App\Form\WebsiteType;
use App\Repository\WebsiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/website")
 */
class WebsiteController extends AbstractController
{

    /**
     * @Route("/{id}", name="website_show", methods={"GET"})
     */
    public function show(Website $website): Response
    {
        return $this->render('website/show.html.twig', [
            'website' => $website,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="website_edit", methods={"GET","POST"})
     */
    public function edit(int $id, Request $request, Website $website): Response
    {
        $form = $this->createForm(WebsiteType::class, $website);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('website_show', ['id' => $id]);
        }

        return $this->render('website/edit.html.twig', [
            'website' => $website,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="website_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Website $website): Response
    {
        if ($this->isCsrfTokenValid('delete'.$website->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($website);
            $entityManager->flush();
        }

        return $this->redirectToRoute('website_index');
    }
}
