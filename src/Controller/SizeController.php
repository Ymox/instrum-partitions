<?php

namespace App\Controller;

use App\Entity\Size;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Size controller.
 *
 */
class SizeController extends AbstractController
{
    /**
     * Lists all size entities.
     *
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();

        $sizes = $em->getRepository(Size::class)->findAll();

        return $this->render('size/index.html.twig', array(
            'sizes' => $sizes,
        ));
    }

    /**
     * Creates a new size entity.
     *
     */
    public function new(Request $request)
    {
        $size = new Size();
        $form = $this->createForm('App\Form\SizeType', $size);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($size);
            $em->flush();

            return $this->redirectToRoute('size_index');
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
    public function show(Size $size)
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
    public function edit(Request $request, Size $size)
    {
        $deleteForm = $this->createDeleteForm($size);
        $editForm = $this->createForm('App\Form\SizeType', $size);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('size_index');
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
    public function delete(Request $request, Size $size)
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
