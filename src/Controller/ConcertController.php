<?php

namespace App\Controller;

use App\Entity\Concert;
use App\Repository\ConcertRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/concert', name: 'concert_')]
class ConcertController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(Request $request, ConcertRepository $concertRepository): Response
    {
        $concerts = $concertRepository->findBy(
            [],
            ['date' => 'DESC'],
            $this->getParameter('paginate.per_page'),
            ($request->query->get('page', 1) - 1) * $this->getParameter('paginate.per_page')
        );
        $nbConcerts = $concertRepository->countAll();

        return $this->render('concert/index.html.twig', [
            'concerts' => $concerts,
            'nbPages' => max(ceil($nbConcerts / $this->getParameter('paginate.per_page')), 1),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $concert = new Concert();
        $form = $this->createForm(\App\Form\ConcertType::class, $concert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($concert);
            $em->flush();

            return $this->redirectToRoute('concert_show', ['id' => $concert->getId()]);
        }

        return $this->render('concert/new.html.twig', [
            'concert' => $concert,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/show', name: 'show')]
    public function show(Concert $concert): Response
    {
        $deleteForm = $this->createDeleteForm($concert);

        return $this->render('concert/show.html.twig', [
            'concert' => $concert,
            'delete_form' => $deleteForm,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Concert $concert, EntityManagerInterface $em): Response
    {
        $deleteForm = $this->createDeleteForm($concert);
        $editForm = $this->createForm(\App\Form\ConcertType::class, $concert);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->flush();

            return $this->redirectToRoute('concert_edit', ['id' => $concert->getId()]);
        }

        return $this->render('concert/edit.html.twig', [
            'concert' => $concert,
            'edit_form' => $editForm,
            'delete_form' => $deleteForm,
        ]);
    }

    #[Route('/{id}/delete', name: 'delete', methods: ['DELETE'])]
    public function delete(Request $request, Concert $concert, EntityManagerInterface $em): Response
    {
        $form = $this->createDeleteForm($concert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->remove($concert);
            $em->flush();
        }

        return $this->redirectToRoute('concert_index');
    }

    private function createDeleteForm(Concert $concert): Form
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('concert_delete', ['id' => $concert->getId()]))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
