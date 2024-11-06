<?php

namespace App\Controller;

use App\Entity\Program;
use App\Repository\ProgramRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/program', name: 'program_')]
class ProgramController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(Request $request, ProgramRepository $programRepository): Response
    {
        $programs = $programRepository->findBy(
            [],
            [
                'updatedAt' => 'DESC',
                'createdAt' => 'DESC'
            ],
            $this->getParameter('paginate.per_page'),
            ($request->query->get('page', 1) - 1) * $this->getParameter('paginate.per_page')
        );
        $nbPrograms = $programRepository->countAll();

        return $this->render('program/index.html.twig', [
            'programs' => $programs,
            'nbPages' => max(ceil($nbPrograms / $this->getParameter('paginate.per_page')), 1),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $program = new Program();
        $form = $this->createForm(\App\Form\ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($program);
            $em->flush();

            return $this->redirectToRoute('program_show', ['id' => $program->getId()]);
        }

        return $this->render('program/new.html.twig', [
            'program' => $program,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/show', name: 'show')]
    public function show(#[MapEntity] Program $program): Response
    {
        $deleteForm = $this->createDeleteForm($program);

        return $this->render('program/show.html.twig', [
            'program' => $program,
            'delete_form' => $deleteForm,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, #[MapEntity] Program $program, EntityManagerInterface $em): Response
    {
        $deleteForm = $this->createDeleteForm($program);
        $editForm = $this->createForm(\App\Form\ProgramType::class, $program);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $program->setUpdatedAt(new \DateTime());
            $em->flush();

            return $this->redirectToRoute('program_edit', ['id' => $program->getId()]);
        }

        return $this->render('program/edit.html.twig', [
            'program' => $program,
            'edit_form' => $editForm,
            'delete_form' => $deleteForm,
        ]);
    }

    #[Route('/{id}/delete', name: 'delete', methods: ['DELETE'])]
    public function delete(Request $request, #[MapEntity] Program $program, EntityManagerInterface $em): Response
    {
        $form = $this->createDeleteForm($program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->remove($program);
            $em->flush();
        }

        return $this->redirectToRoute('program_index');
    }

    private function createDeleteForm(Program $program): Form
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('program_delete', ['id' => $program->getId()]))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
