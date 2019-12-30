<?php

namespace App\Controller;

use App\Entity\Instrument;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Instrument controller.
 *
 */
class InstrumentController extends AbstractController
{
    /**
     * Lists all instrument entities.
     *
     */
    public function index(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $instruments = $em->getRepository(Instrument::class)->paginateBy(array(), array('common' => 'DESC'), $this->getParameter('paginate.per_page'), ($request->query->get('page', 1) - 1) * $this->getParameter('paginate.per_page'));

        return $this->render('instrument/index.html.twig', array(
            'instruments' => $instruments,
            'nbPages' => max(ceil($instruments->count() / $this->getParameter('paginate.per_page')), 1),
        ));
    }

    /**
     * Creates a new instrument entity.
     *
     */
    public function new(Request $request)
    {
        $instrument = new Instrument();
        $form = $this->createForm('App\Form\InstrumentType', $instrument);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($instrument);
            $em->flush();

            return $this->redirectToRoute('instrument_index');
        }

        return $this->render('instrument/new.html.twig', array(
            'instrument' => $instrument,
            'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing instrument entity.
     *
     */
    public function edit(Request $request, Instrument $instrument)
    {
        $deleteForm = $this->createDeleteForm($instrument);
        $editForm = $this->createForm('App\Form\InstrumentType', $instrument);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('instrument_edit', array('id' => $instrument->getId()));
        }

        return $this->render('instrument/edit.html.twig', array(
            'instrument' => $instrument,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a instrument entity.
     *
     */
    public function delete(Request $request, Instrument $instrument)
    {
        $form = $this->createDeleteForm($instrument);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($instrument);
            $em->flush();
        }

        return $this->redirectToRoute('instrument_index');
    }

    /**
     * Creates a form to delete a instrument entity.
     *
     * @param Instrument $instrument The instrument entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Instrument $instrument)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('instrument_delete', array('id' => $instrument->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
