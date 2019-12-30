<?php

namespace App\Controller;

use App\Entity\Missing;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;


/**
 * Missing controller.
 *
 */
class MissingController extends AbstractController
{
    /**
     * Lists all missing entities.
     *
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();

        $missings = $em->getRepository(Missing::class)->findAll();

        return $this->render('missing/index.html.twig', array(
            'missings' => $missings,
        ));
    }

    /**
     * Deletes a missing entity.
     *
     */
    public function delete(Request $request, Missing $missing)
    {
        $form = $this->createDeleteForm($missing);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($missing);
            $em->flush();
        }

        return $this->redirectToRoute('missing_index');
    }

    /**
     * Creates a form to delete a piece entity.
     *
     * @param Missing $missing The piece entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Missing $missing)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('missing_delete', array('id' => $missing->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}