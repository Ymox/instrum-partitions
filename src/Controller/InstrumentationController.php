<?php

namespace App\Controller;

use App\Entity\Instrumentation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Instrumentation controller.
 *
 */
class InstrumentationController extends AbstractController
{
    /**
     * Lists all instrumentation entities.
     *
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();

        $instrumentations = $em->getRepository(Instrumentation::class)->findAll();

        return $this->render('instrumentation/index.html.twig', [
            'instrumentations' => $instrumentations,
        ]);
    }

    /**
     * Creates a new instrumentation entity.
     *
     */
    public function new(Request $request)
    {
        $instrumentation = new Instrumentation();
        $form = $this->createForm(\App\Form\InstrumentationType::class, $instrumentation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($instrumentation);
            $em->flush();

            return $this->redirectToRoute('instrumentation_index');
        }

        return $this->render('instrumentation/new.html.twig', [
            'instrumentation' => $instrumentation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing instrumentation entity.
     *
     */
    public function edit(Request $request, Instrumentation $instrumentation)
    {
        $deleteForm = $this->createDeleteForm($instrumentation);
        $editForm = $this->createForm(\App\Form\InstrumentationType::class, $instrumentation);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('instrumentation_index');
        }

        return $this->render('instrumentation/edit.html.twig', [
            'instrumentation' => $instrumentation,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Deletes a instrumentation entity.
     *
     */
    public function delete(Request $request, Instrumentation $instrumentation)
    {
        $form = $this->createDeleteForm($instrumentation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($instrumentation);
            $em->flush();
        }

        return $this->redirectToRoute('instrumentation_index');
    }

    /**
     * Creates a form to delete a instrumentation entity.
     *
     * @param Instrumentation $instrumentation The instrumentation entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Instrumentation $instrumentation)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('instrumentation_delete', ['id' => $instrumentation->getId()]))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
