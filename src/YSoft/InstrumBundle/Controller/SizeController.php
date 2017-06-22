<?php

namespace YSoft\InstrumBundle\Controller;

use YSoft\InstrumBundle\Entity\Size;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Size controller.
 *
 */
class SizeController extends Controller
{
    /**
     * Lists all size entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $sizes = $em->getRepository('YSoftInstrumBundle:Size')->findAll();

        return $this->render('size/index.html.twig', array(
            'sizes' => $sizes,
        ));
    }

    /**
     * Creates a new size entity.
     *
     */
    public function newAction(Request $request)
    {
        $size = new Size();
        $form = $this->createForm('YSoft\InstrumBundle\Form\SizeType', $size);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($size);
            $em->flush();

            return $this->redirectToRoute('size_show', array('name' => $size->getName()));
        }

        return $this->render('size/new.html.twig', array(
            'size' => $size,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a size entity.
     *
     */
    public function showAction(Size $size)
    {
        $deleteForm = $this->createDeleteForm($size);

        return $this->render('size/show.html.twig', array(
            'size' => $size,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing size entity.
     *
     */
    public function editAction(Request $request, Size $size)
    {
        $deleteForm = $this->createDeleteForm($size);
        $editForm = $this->createForm('YSoft\InstrumBundle\Form\SizeType', $size);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('size_edit', array('name' => $size->getName()));
        }

        return $this->render('size/edit.html.twig', array(
            'size' => $size,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a size entity.
     *
     */
    public function deleteAction(Request $request, Size $size)
    {
        $form = $this->createDeleteForm($size);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($size);
            $em->flush();
        }

        return $this->redirectToRoute('size_index');
    }

    /**
     * Creates a form to delete a size entity.
     *
     * @param Size $size The size entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Size $size)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('size_delete', array('name' => $size->getName())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
