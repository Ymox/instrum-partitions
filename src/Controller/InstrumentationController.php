<?php

namespace App\Controller;

use App\Entity\Instrumentation;
use App\Repository\InstrumentationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/instrumentation', name: 'instrumentation_')]
class InstrumentationController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(InstrumentationRepository $instrumentationRepository): Response
    {
        $instrumentations = $instrumentationRepository->findAll();

        return $this->render('instrumentation/index.html.twig', [
            'instrumentations' => $instrumentations,
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $instrumentation = new Instrumentation();
        $form = $this->createForm(\App\Form\InstrumentationType::class, $instrumentation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($instrumentation);
            $em->flush();

            return $this->redirectToRoute('instrumentation_index');
        }

        return $this->render('instrumentation/new.html.twig', [
            'instrumentation' => $instrumentation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, #[MapEntity] Instrumentation $instrumentation, EntityManagerInterface $em): Response
    {
        $deleteForm = $this->createDeleteForm($instrumentation);
        $editForm = $this->createForm(\App\Form\InstrumentationType::class, $instrumentation);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->flush();

            return $this->redirectToRoute('instrumentation_index');
        }

        return $this->render('instrumentation/edit.html.twig', [
            'instrumentation' => $instrumentation,
            'edit_form' => $editForm,
            'delete_form' => $deleteForm,
        ]);
    }

    #[Route('/{id}/delete', name: 'delete', methods: ['DELETE'])]
    public function delete(Request $request, #[MapEntity] Instrumentation $instrumentation, EntityManagerInterface $em): Response
    {
        $form = $this->createDeleteForm($instrumentation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->remove($instrumentation);
            $em->flush();
        }

        return $this->redirectToRoute('instrumentation_index');
    }

    private function createDeleteForm(Instrumentation $instrumentation): Form
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('instrumentation_delete', ['id' => $instrumentation->getId()]))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
