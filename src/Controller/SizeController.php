<?php

namespace App\Controller;

use App\Entity\Size;
use App\Repository\SizeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/size', name: 'size_')]
class SizeController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(SizeRepository $sizeRepository): Response
    {
        $sizes = $sizeRepository->findBy([], ['minHeight' => 'asc']);

        return $this->render('size/index.html.twig', [
            'sizes' => $sizes,
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $size = new Size();
        $form = $this->createForm(\App\Form\SizeType::class, $size);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($size);
            $em->flush();

            return $this->redirectToRoute('size_index');
        }

        return $this->render('size/new.html.twig', [
            'size' => $size,
            'form' => $form,
        ]);
    }

    #[Route('/{name}/show', name: 'show')]
    public function show(#[MapEntity(id: 'name')] Size $size): Response
    {
        $deleteForm = $this->createDeleteForm($size);

        return $this->render('size/show.html.twig', [
            'size' => $size,
            'delete_form' => $deleteForm,
        ]);
    }

    #[Route('/{name}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, #[MapEntity(id: 'name')] Size $size, EntityManagerInterface $em): Response
    {
        $deleteForm = $this->createDeleteForm($size);
        $editForm = $this->createForm(\App\Form\SizeType::class, $size);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->flush();

            return $this->redirectToRoute('size_index');
        }

        return $this->render('size/edit.html.twig', [
            'size' => $size,
            'edit_form' => $editForm,
            'delete_form' => $deleteForm,
        ]);
    }

    #[Route('/{name}/delete', name: 'delete', methods: ['DELETE'])]
    public function delete(Request $request, #[MapEntity(id: 'name')] Size $size, EntityManagerInterface $em): Response
    {
        $form = $this->createDeleteForm($size);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->remove($size);
            $em->flush();
        }

        return $this->redirectToRoute('size_index');
    }

    private function createDeleteForm(Size $size): Form
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('size_delete', ['name' => $size->getName()]))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
