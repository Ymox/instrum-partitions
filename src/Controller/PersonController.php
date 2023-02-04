<?php

namespace App\Controller;

use App\Entity\Person;
use App\Repository\PersonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/person', name: 'person_')]
class PersonController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(Request $request, PersonRepository $personRepository): Response
    {
        $people = $personRepository->paginateBy([], ['lastName' => 'ASC'], $this->getParameter('paginate.per_page'), ($request->query->get('page', 1) - 1) * $this->getParameter('paginate.per_page'));

        return $this->render('person/index.html.twig', [
            'people'  => $people,
            'nbPages' => max(ceil($people->count() / $this->getParameter('paginate.per_page')), 1),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $person = new Person();
        $form = $this->createForm(\App\Form\PersonType::class, $person, [
            'action' => $this->generateUrl('person_new'),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($person);
            $em->flush();

            if ($request->isXmlHttpRequest()) {
                return $this->json([
                    'value' => $person->getId(),
                    'firstName' => $person->getFirstName(),
                    'lastName' => $person->getLastName(),
                    'text' => (string)$person,
                ]);
            }
            return $this->redirectToRoute('person_show', ['id' => $person->getId()]);
        }

        return $this->render('person/new.html.twig', [
            'person' => $person,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/show', name: 'show')]
    public function show(Person $person): Response
    {
        $deleteForm = $this->createDeleteForm($person);

        return $this->render('person/show.html.twig', [
            'person' => $person,
            'delete_form' => $deleteForm,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Person $person, EntityManagerInterface $em): Response
    {
        $deleteForm = $this->createDeleteForm($person);
        $editForm = $this->createForm(\App\Form\PersonType::class, $person);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->flush();

            return $this->redirectToRoute('person_show', ['id' => $person->getId()]);
        }

        return $this->render('person/edit.html.twig', [
            'person' => $person,
            'edit_form' => $editForm,
            'delete_form' => $deleteForm,
        ]);
    }

    #[Route('/{id}/delete', name: 'delete', methods: ['DELETE'])]
    public function delete(Request $request, Person $person, EntityManagerInterface $em): Response
    {
        $form = $this->createDeleteForm($person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->remove($person);
            $em->flush();
        }

        return $this->redirectToRoute('person_index');
    }

    private function createDeleteForm(Person $person): Form
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('person_delete', ['id' => $person->getId()]))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
