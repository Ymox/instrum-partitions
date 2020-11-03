<?php

namespace App\Controller;

use App\Entity\Program;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Program controller.
 *
 */
class ProgramController extends AbstractController
{
    /**
     * Lists all program entities.
     *
     */
    public function index(Request $request)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository(Program::class);

        $programs = $repository->findBy(
            array(),
            array('updatedAt' => 'DESC', 'createdAt' => 'DESC'),
            $this->getParameter('paginate.per_page'),
            ($request->query->get('page', 1) - 1) * $this->getParameter('paginate.per_page')
        );
        $nbPrograms = $repository->countAll();

        return $this->render('program/index.html.twig', array(
            'programs' => $programs,
            'nbPages' => max(ceil($nbPrograms / $this->getParameter('paginate.per_page')), 1),
        ));
    }

    /**
     * Creates a new program entity.
     *
     */
    public function new(Request $request)
    {
        $program = new Program();
        $form = $this->createForm(\App\Form\ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($program);
            $em->flush();

            return $this->redirectToRoute('program_show', array('id' => $program->getId()));
        }

        return $this->render('program/new.html.twig', array(
            'program' => $program,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a program entity.
     *
     */
    public function show(Program $program)
    {
        $deleteForm = $this->createDeleteForm($program);

        return $this->render('program/show.html.twig', array(
            'program' => $program,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing program entity.
     *
     */
    public function edit(Request $request, Program $program)
    {
        $deleteForm = $this->createDeleteForm($program);
        $editForm = $this->createForm(\App\Form\ProgramType::class, $program);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('program_edit', array('id' => $program->getId()));
        }

        return $this->render('program/edit.html.twig', array(
            'program' => $program,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a program entity.
     *
     */
    public function delete(Request $request, Program $program)
    {
        $form = $this->createDeleteForm($program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($program);
            $em->flush();
        }

        return $this->redirectToRoute('program_index');
    }

    /**
     * Creates a form to delete a program entity.
     *
     * @param Program $program The program entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Program $program)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('program_delete', array('id' => $program->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
