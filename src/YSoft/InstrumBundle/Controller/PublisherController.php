<?php

namespace YSoft\InstrumBundle\Controller;

use YSoft\InstrumBundle\Entity\Publisher;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Publisher controller.
 *
 */
class PublisherController extends Controller
{
    /**
     * Lists all publisher entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $publishers = $em->getRepository('YSoftInstrumBundle:Publisher')->findAll();

        return $this->render('publisher/index.html.twig', array(
            'publishers' => $publishers,
        ));
    }

    /**
     * Creates a new publisher entity.
     *
     */
    public function newAction(Request $request)
    {
        $publisher = new Publisher();
        $form = $this->createForm('YSoft\InstrumBundle\Form\PublisherType', $publisher);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($publisher);
            $em->flush();

            return $this->redirectToRoute('publisher_index');
        }

        return $this->render('publisher/new.html.twig', array(
            'publisher' => $publisher,
            'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing publisher entity.
     *
     */
    public function editAction(Request $request, Publisher $publisher)
    {
        $deleteForm = $this->createDeleteForm($publisher);
        $editForm = $this->createForm('YSoft\InstrumBundle\Form\PublisherType', $publisher);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('publisher_index');
        }

        return $this->render('publisher/edit.html.twig', array(
            'publisher' => $publisher,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a publisher entity.
     *
     */
    public function deleteAction(Request $request, Publisher $publisher)
    {
        $form = $this->createDeleteForm($publisher);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($publisher);
            $em->flush();
        }

        return $this->redirectToRoute('publisher_index');
    }

    /**
     * Creates a form to delete a publisher entity.
     *
     * @param Publisher $publisher The publisher entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Publisher $publisher)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('publisher_delete', array('id' => $publisher->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
