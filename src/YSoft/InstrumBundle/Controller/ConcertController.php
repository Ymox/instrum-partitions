<?php

namespace YSoft\InstrumBundle\Controller;

use YSoft\InstrumBundle\Entity\Concert;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Concert controller.
 *
 */
class ConcertController extends Controller
{
    /**
     * Lists all concert entities.
     *
     */
    public function indexAction(Request $request)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('YSoftInstrumBundle:Concert');

        $concerts = $repository->findBy(
            array(),
            array('date' => 'DESC'),
            $this->getParameter('paginate.per_page'),
            ($request->query->get('page', 1) - 1) * $this->getParameter('paginate.per_page')
        );
        $nbConcerts = $repository->countAll();

        return $this->render('concert/index.html.twig', array(
            'concerts' => $concerts,
            'nbPages' => max(ceil($nbConcerts / $this->getParameter('paginate.per_page')), 1),
        ));
    }

    /**
     * Creates a new concert entity.
     *
     */
    public function newAction(Request $request)
    {
        $concert = new Concert();
        $form = $this->createForm('YSoft\InstrumBundle\Form\ConcertType', $concert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($concert);
            $em->flush();

            return $this->redirectToRoute('concert_show', array('id' => $concert->getId()));
        }

        return $this->render('concert/new.html.twig', array(
            'concert' => $concert,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a concert entity.
     *
     */
    public function showAction(Concert $concert)
    {
        $deleteForm = $this->createDeleteForm($concert);

        return $this->render('concert/show.html.twig', array(
            'concert' => $concert,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing concert entity.
     *
     */
    public function editAction(Request $request, Concert $concert)
    {
        $deleteForm = $this->createDeleteForm($concert);
        $editForm = $this->createForm('YSoft\InstrumBundle\Form\ConcertType', $concert);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('concert_edit', array('id' => $concert->getId()));
        }

        return $this->render('concert/edit.html.twig', array(
            'concert' => $concert,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a concert entity.
     *
     */
    public function deleteAction(Request $request, Concert $concert)
    {
        $form = $this->createDeleteForm($concert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($concert);
            $em->flush();
        }

        return $this->redirectToRoute('concert_index');
    }

    /**
     * Creates a form to delete a concert entity.
     *
     * @param Concert $concert The concert entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Concert $concert)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('concert_delete', array('id' => $concert->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
