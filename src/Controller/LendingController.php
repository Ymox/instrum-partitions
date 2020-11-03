<?php

namespace App\Controller;

use App\Entity\Lending;
use App\Entity\Piece;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Lending controller.
 *
 */
class LendingController extends AbstractController
{
    /**
     * Lists all lending entities.
     *
     */
    public function index(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $repo = $em->getRepository(Lending::class);

        $lendings = $repo->searchBy(
            $request->query->get('q', array()),
            array(
                $request->query->get('field', 'id') => $request->query->get('direction', 'asc'),
            ),
            $this->getParameter('paginate.per_page'),
            ($request->query->get('page', 1) - 1) * $this->getParameter('paginate.per_page')
        );

        return $this->render('lending/index.html.twig', array(
            'lendings' => $lendings,
            'nbPages'  => max(ceil($lendings->count() / $this->getParameter('paginate.per_page')), 1)
        ));
    }

    /**
     * Creates a new lending entity.
     *
     */
    public function new(Request $request)
    {
        $lending = new Lending();
        $form = $this->createForm(\App\Form\LendingType::class, $lending);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->handlePiecesInLending($lending);
            $em = $this->getDoctrine()->getManager();
            $em->persist($lending);
            $em->flush();

            return $this->redirectToRoute('lending_show', array('id' => $lending->getId()));
        }

        return $this->render('lending/new.html.twig', array(
            'lending' => $lending,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a lending entity.
     *
     */
    public function show(Lending $lending)
    {
        $deleteForm = $this->createDeleteForm($lending);

        return $this->render('lending/show.html.twig', array(
            'lending' => $lending,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing lending entity.
     *
     */
    public function edit(Request $request, Lending $lending)
    {
        $deleteForm = $this->createDeleteForm($lending);
        $editForm = $this->createForm(\App\Form\LendingType::class, $lending);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->handlePiecesInLending($lending);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('lending_edit', array('id' => $lending->getId()));
        }

        return $this->render('lending/edit.html.twig', array(
            'lending' => $lending,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a lending entity.
     *
     */
    public function delete(Request $request, Lending $lending)
    {
        $form = $this->createDeleteForm($lending);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($lending);
            $em->flush();
        }

        return $this->redirectToRoute('lending_index');
    }

    /**
     * Creates a form to delete a lending entity.
     *
     * @param Lending $lending The lending entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Lending $lending)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('lending_delete', array('id' => $lending->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * Applies location and state(s) to lent or returned pieces
     */
    private function handlePiecesInLending(\App\Entity\Lending &$lending)
    {
        if ($lending->isOurs()) {
            $location = $lending->getEnd() ? Piece::LOCATION_SHELF : Piece::LOCATION_LENT;
        } else {
            $location = $lending->getEnd() ? Piece::LOCATION_RETURNED : Piece::LOCATION_SHELF;
        }

        foreach ($lending->getPieces() as &$piece) {
            $piece->setLocation($location);
            $piece->removeState(Piece::STATE_VERIFIED);
        }
    }
}
