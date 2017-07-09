<?php

namespace YSoft\InstrumBundle\Controller;

use YSoft\InstrumBundle\Entity\Instrumentation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Instrumentation controller.
 *
 */
class InstrumentationController extends Controller
{
    /**
     * Lists all instrumentation entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $instrumentations = $em->getRepository('YSoftInstrumBundle:Instrumentation')->findAll();

        return $this->render('instrumentation/index.html.twig', array(
            'instrumentations' => $instrumentations,
        ));
    }

    /**
     * Creates a new instrumentation entity.
     *
     */
    public function newAction(Request $request)
    {
        $instrumentation = new Instrumentation();
        $form = $this->createForm('YSoft\InstrumBundle\Form\InstrumentationType', $instrumentation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($instrumentation);
            $em->flush();

            return $this->redirectToRoute('instrumentation_index');
        }

        return $this->render('instrumentation/new.html.twig', array(
            'instrumentation' => $instrumentation,
            'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing instrumentation entity.
     *
     */
    public function editAction(Request $request, Instrumentation $instrumentation)
    {
        $deleteForm = $this->createDeleteForm($instrumentation);
        $editForm = $this->createForm('YSoft\InstrumBundle\Form\InstrumentationType', $instrumentation);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('instrumentation_index');
        }

        return $this->render('instrumentation/edit.html.twig', array(
            'instrumentation' => $instrumentation,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a instrumentation entity.
     *
     */
    public function deleteAction(Request $request, Instrumentation $instrumentation)
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
            ->setAction($this->generateUrl('instrumentation_delete', array('id' => $instrumentation->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
