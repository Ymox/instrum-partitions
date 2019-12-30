<?php

namespace App\Controller;

use App\Entity\Person;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Person controller.
 *
 */
class PersonController extends AbstractController
{
    /**
     * Lists all person entities.
     *
     */
    public function index(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $people = $em->getRepository(Person::class)->paginateBy(array(), array('lastName' => 'ASC'), $this->getParameter('paginate.per_page'), ($request->query->get('page', 1) - 1) * $this->getParameter('paginate.per_page'));

        return $this->render('person/index.html.twig', array(
            'people'  => $people,
            'nbPages' => max(ceil($people->count() / $this->getParameter('paginate.per_page')), 1),
        ));
    }

    /**
     * Creates a new person entity.
     *
     */
    public function new(Request $request)
    {
        $person = new Person();
        $form = $this->createForm('App\Form\PersonType', $person, array(
            'action' => $this->generateUrl('person_new'),
        ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($person);
            $em->flush();

            if ($request->isXmlHttpRequest()) {
                return $this->json(array(
                    'value' => $person->getId(),
                    'firstName' => $person->getFirstName(),
                    'lastName' => $person->getLastName(),
                    'text' => (string)$person,
                ));
            }
            return $this->redirectToRoute('person_show', array('id' => $person->getId()));
        }

        return $this->render('person/new.html.twig', array(
            'person' => $person,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a person entity.
     *
     */
    public function show(Person $person)
    {
        $deleteForm = $this->createDeleteForm($person);

        return $this->render('person/show.html.twig', array(
            'person' => $person,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing person entity.
     *
     */
    public function edit(Request $request, Person $person)
    {
        $deleteForm = $this->createDeleteForm($person);
        $editForm = $this->createForm('App\Form\PersonType', $person);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('person_show', array('id' => $person->getId()));
        }

        return $this->render('person/edit.html.twig', array(
            'person' => $person,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a person entity.
     *
     */
    public function delete(Request $request, Person $person)
    {
        $form = $this->createDeleteForm($person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($person);
            $em->flush();
        }

        return $this->redirectToRoute('person_index');
    }

    /**
     * Creates a form to delete a person entity.
     *
     * @param Person $person The person entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Person $person)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('person_delete', array('id' => $person->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
