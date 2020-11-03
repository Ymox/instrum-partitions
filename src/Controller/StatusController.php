<?php

namespace App\Controller;

use App\Entity\Status;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Status controller.
 *
 */
class StatusController extends AbstractController
{
    /**
     * Lists all status entities.
     *
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();

        $statuses = $em->getRepository(Status::class)->findAll();

        return $this->render('status/index.html.twig', array(
            'statuses' => $statuses,
        ));
    }

    /**
     * Creates a new status entity.
     *
     */
    public function new(Request $request)
    {
        $status = new Status();
        $form = $this->createForm(\App\Form\StatusType::class, $status);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($status);
            $em->flush();

            return $this->redirectToRoute('status_index');
        }

        return $this->render('status/new.html.twig', array(
            'status' => $status,
            'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing status entity.
     *
     */
    public function edit(Request $request, Status $status)
    {
        $deleteForm = $this->createDeleteForm($status);
        $editForm = $this->createForm(\App\Form\StatusType::class, $status);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('status_index');
        }

        return $this->render('status/edit.html.twig', array(
            'status' => $status,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a status entity.
     *
     */
    public function delete(Request $request, Status $status)
    {
        $form = $this->createDeleteForm($status);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($status);
            $em->flush();
        }

        return $this->redirectToRoute('status_index');
    }

    /**
     * Creates a form to delete a status entity.
     *
     * @param Status $status The status entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Status $status)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('status_delete', array('id' => $status->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
