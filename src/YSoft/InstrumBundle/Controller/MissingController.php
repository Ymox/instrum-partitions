<?php

namespace YSoft\InstrumBundle\Controller;

use YSoft\InstrumBundle\Entity\Missing;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


/**
 * Missing controller.
 *
 */
class MissingController extends Controller
{
    /**
     * Lists all missing entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $missings = $em->getRepository('YSoftInstrumBundle:Missing')->findAll();

        return $this->render('missing/index.html.twig', array(
            'missings' => $missings,
        ));
    }

    /**
     * Deletes a missing entity.
     *
     */
    public function deleteAction(Request $request, Missing $missing)
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