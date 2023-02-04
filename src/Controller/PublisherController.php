<?php

namespace App\Controller;

use App\Entity\Publisher;
use App\Repository\PublisherRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/publisher', name: 'publisher_')]
class PublisherController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(PublisherRepository $publisherRepository): Response
    {
        $publishers = $publisherRepository->findAll();

        return $this->render('publisher/index.html.twig', [
            'publishers' => $publishers,
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $publisher = new Publisher();
        $form = $this->createForm(\App\Form\PublisherType::class, $publisher);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($publisher);
            $em->flush();

            return $this->redirectToRoute('publisher_index');
        }

        return $this->render('publisher/new.html.twig', [
            'publisher' => $publisher,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Publisher $publisher, EntityManagerInterface $em): Response
    {
        $deleteForm = $this->createDeleteForm($publisher);
        $editForm = $this->createForm(\App\Form\PublisherType::class, $publisher);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->flush();

            return $this->redirectToRoute('publisher_index');
        }

        return $this->render('publisher/edit.html.twig', [
            'publisher' => $publisher,
            'edit_form' => $editForm,
            'delete_form' => $deleteForm,
        ]);
    }

    #[Route('/{id}/delete', name: 'delete', methods: ['DELETE'])]
    public function delete(Request $request, Publisher $publisher, EntityManagerInterface $em): Response
    {
        $form = $this->createDeleteForm($publisher);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->remove($publisher);
            $em->flush();
        }

        return $this->redirectToRoute('publisher_index');
    }

    private function createDeleteForm(Publisher $publisher): Form
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('publisher_delete', ['id' => $publisher->getId()]))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
