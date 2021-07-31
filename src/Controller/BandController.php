<?php

namespace App\Controller;

use App\Entity\Band;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Band controller.
 *
 */
class BandController extends AbstractController
{
    /**
     * Lists all band entities.
     *
     */
    public function index(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $bands = $em->getRepository(Band::class)->paginateBy([], ['name' => 'ASC'], $this->getParameter('paginate.per_page'), ($request->query->get('page', 1) - 1) * $this->getParameter('paginate.per_page'));

        return $this->render('band/index.html.twig', [
            'bands' => $bands,
            'nbPages' => max(ceil($bands->count() / $this->getParameter('paginate.per_page')), 1),
        ]);
    }

    /**
     * Creates a new band entity.
     *
     */
    public function new(Request $request)
    {
        $band = new Band();
        $form = $this->createForm(\App\Form\BandType::class, $band, [
            'action' => $this->generateUrl('band_new'),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($band);
            $em->flush();

            if ($request->isXmlHttpRequest()) {
                return $this->json([
                    'value' => $band->getId(),
                    'name' => $band->getName(),
                    'text' => (string)$band,
                ]);
            }

            return $this->redirectToRoute('band_show', ['id' => $band->getId()]);
        }

        return $this->render('band/new.html.twig', [
            'band' => $band,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a band entity.
     *
     */
    public function show(Band $band)
    {
        $deleteForm = $this->createDeleteForm($band);

        return $this->render('band/show.html.twig', [
            'band' => $band,
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing band entity.
     *
     */
    public function edit(Request $request, Band $band)
    {
        $deleteForm = $this->createDeleteForm($band);
        $editForm = $this->createForm(\App\Form\BandType::class, $band);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('band_edit', ['id' => $band->getId()]);
        }

        return $this->render('band/edit.html.twig', [
            'band' => $band,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Deletes a band entity.
     *
     */
    public function delete(Request $request, Band $band)
    {
        $form = $this->createDeleteForm($band);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($band);
            $em->flush();
        }

        return $this->redirectToRoute('band_index');
    }

    /**
     * Creates a form to delete a band entity.
     *
     * @param Band $band The band entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Band $band)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('band_delete', ['id' => $band->getId()]))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
