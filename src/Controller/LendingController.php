<?php

namespace App\Controller;

use App\Config\Location;
use App\Config\State;
use App\Entity\Lending;
use App\Repository\LendingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/lending', name: 'lending_')]
class LendingController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(Request $request, LendingRepository $lendingRepository): Response
    {
        $lendings = $lendingRepository->searchBy(
            $request->query->has('q') ? $request->query->all('q') : [],
            [
                $request->query->get('field', 'l.start') => $request->query->get('direction', 'desc'),
            ],
            $this->getParameter('paginate.per_page'),
            ($request->query->get('page', 1) - 1) * $this->getParameter('paginate.per_page')
        );

        return $this->render('lending/index.html.twig', [
            'lendings' => $lendings,
            'nbPages'  => max(ceil($lendings->count() / $this->getParameter('paginate.per_page')), 1)
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $lending = new Lending();
        $form = $this->createForm(\App\Form\LendingType::class, $lending);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->handlePiecesInLending($lending);
            $em->persist($lending);
            $em->flush();

            return $this->redirectToRoute('lending_show', ['id' => $lending->getId()]);
        }

        return $this->render('lending/new.html.twig', [
            'lending' => $lending,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/show', name: 'show')]
    public function show(#[MapEntity] Lending $lending): Response
    {
        $deleteForm = $this->createDeleteForm($lending);

        return $this->render('lending/show.html.twig', [
            'lending' => $lending,
            'delete_form' => $deleteForm,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, #[MapEntity] Lending $lending, EntityManagerInterface $em): Response
    {
        $deleteForm = $this->createDeleteForm($lending);
        $editForm = $this->createForm(\App\Form\LendingType::class, $lending);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->handlePiecesInLending($lending);
            $em->flush();

            return $this->redirectToRoute('lending_edit', ['id' => $lending->getId()]);
        }

        return $this->render('lending/edit.html.twig', [
            'lending' => $lending,
            'edit_form' => $editForm,
            'delete_form' => $deleteForm,
        ]);
    }

    #[Route('/{id}/delete', name: 'delete', methods: ['DELETE'])]
    public function delete(Request $request, #[MapEntity] Lending $lending, EntityManagerInterface $em): Response
    {
        $form = $this->createDeleteForm($lending);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->remove($lending);
            $em->flush();
        }

        return $this->redirectToRoute('lending_index');
    }

    private function createDeleteForm(Lending $lending): Form
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('lending_delete', ['id' => $lending->getId()]))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    private function handlePiecesInLending(Lending &$lending): void
    {
        if ($lending->isOurs()) {
            $location = $lending->getEnd() ? Location::SHELF : Location::LENT;
        } else {
            $location = $lending->getEnd() ? Location::RETURNED : Location::SHELF;
        }

        foreach ($lending->getPieces() as &$piece) {
            $piece->setLocation($location);
            $piece->removeState(State::VERIFIED);
        }
    }

    #[Route('/{id}/print', name: 'print')]
    public function print(#[MapEntity] Lending $lending): Response
    {
        return $this->render('lending/print.html.twig', [
            'lending' => $lending,
        ]);
    }
}
