<?php

namespace App\Controller;

use App\Entity\Type;
use App\Repository\TypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/type', name: 'type_')]
class TypeController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(TypeRepository $typeRepository): Response
    {
        $types = $typeRepository->findAll();

        return $this->render('type/index.html.twig', [
            'types' => $types,
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $type = new Type();
        $form = $this->createForm(\App\Form\TypeType::class, $type);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($type);
            $em->flush();

            return $this->redirectToRoute('type_index');
        }

        return $this->render('type/new.html.twig', [
            'type' => $type,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, #[MapEntity] Type $type, EntityManagerInterface $em): Response
    {
        $deleteForm = $this->createDeleteForm($type);
        $editForm = $this->createForm(\App\Form\TypeType::class, $type);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->flush();

            return $this->redirectToRoute('type_index');
        }

        return $this->render('type/edit.html.twig', [
            'type' => $type,
            'edit_form' => $editForm,
            'delete_form' => $deleteForm,
        ]);
    }

    #[Route('/{id}/delete', name: 'delete', methods: ['DELETE'])]
    public function delete(Request $request, #[MapEntity] Type $type, EntityManagerInterface $em): Response
    {
        $form = $this->createDeleteForm($type);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->remove($type);
            $em->flush();
        }

        return $this->redirectToRoute('type_index');
    }

    private function createDeleteForm(Type $type): Form
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('type_delete', ['id' => $type->getId()]))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
