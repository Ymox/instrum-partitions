<?php

namespace App\Controller;

use App\Entity\Instrument;
use App\Repository\InstrumentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/instrument', name: 'instrument_')]
class InstrumentController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(Request $request, InstrumentRepository $instrumentRepository): Response
    {
        $instruments = $instrumentRepository->paginateBy([], ['common' => 'DESC'], $this->getParameter('paginate.per_page'), ($request->query->get('page', 1) - 1) * $this->getParameter('paginate.per_page'));

        return $this->render('instrument/index.html.twig', [
            'instruments' => $instruments,
            'nbPages' => max(ceil($instruments->count() / $this->getParameter('paginate.per_page')), 1),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $instrument = new Instrument();
        $form = $this->createForm(\App\Form\InstrumentType::class, $instrument);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($instrument);
            $em->flush();

            return $this->redirectToRoute('instrument_index');
        }

        return $this->render('instrument/new.html.twig', [
            'instrument' => $instrument,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Instrument $instrument, EntityManagerInterface $em): Response
    {
        $deleteForm = $this->createDeleteForm($instrument);
        $editForm = $this->createForm(\App\Form\InstrumentType::class, $instrument);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->flush();

            return $this->redirectToRoute('instrument_edit', ['id' => $instrument->getId()]);
        }

        return $this->render('instrument/edit.html.twig', [
            'instrument' => $instrument,
            'edit_form' => $editForm,
            'delete_form' => $deleteForm,
        ]);
    }

    #[Route('/{id}/delete', name: 'delete', methods: ['DELETE'])]
    public function delete(Request $request, Instrument $instrument, EntityManagerInterface $em): Response
    {
        $form = $this->createDeleteForm($instrument);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->remove($instrument);
            $em->flush();
        }

        return $this->redirectToRoute('instrument_index');
    }

    private function createDeleteForm(Instrument $instrument): Form
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('instrument_delete', ['id' => $instrument->getId()]))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
