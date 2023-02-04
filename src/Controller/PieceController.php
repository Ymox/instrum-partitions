<?php

namespace App\Controller;

use App\Entity\Piece;
use App\Entity\Concert;
use App\Repository\PieceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class PieceController extends AbstractController
{
    #[Route('/', name: 'piece_index')]
    public function index(Request $request, PieceRepository $pieceRepository): Response
    {

        if ($request->query->has('q') && $request->query->all('q')['id'] && ($piece = $pieceRepository->find($request->query->all('q')['id']))) {
            if ($piece->getWork()) {
                return $this->redirectToRoute('piece_show', ['id' => $piece->getWork()->getId()]);
            } else {
                return $this->redirectToRoute('piece_show', ['id' => $piece->getId()]);
            }
        }

        $pieces = $pieceRepository->searchBy(
            $request->query->has('q') ? $request->query->all('q') : [],
            [
                $request->query->get('field', 'id') => $request->query->get('direction', 'asc'),
            ],
            $this->getParameter('paginate.per_page'),
            ($request->query->get('page', 1) - 1) * $this->getParameter('paginate.per_page')
        );

        return $this->render('piece/index.html.twig', [
            'pieces' => $pieces,
            'nbPages' => max(ceil($pieces->count() / $this->getParameter('paginate.per_page')), 1)
        ]);
    }

    #[Route('/piece/new', name: 'piece_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TranslatorInterface $translator, EntityManagerInterface $em): Response
    {
        $piece = new Piece();
        $piece
            ->setLocation(Piece::LOCATION_SHELF)
            ->setStates(0)
        ;
        $form = $this->createForm(\App\Form\PieceType::class, $piece);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($piece);
            $em->flush();

            $this->addFlash(
                'success',
                $translator->trans(
                    'app.flash.success.creation.piece',
                    [
                        'id'   => $piece->getId(),
                        'name' => $piece->getName(),
                    ]
                )
            );
            return $this->redirectToRoute('piece_new');
        }

        return $this->render('piece/new.html.twig', [
            'piece' => $piece,
            'form' => $form,
        ]);
    }

    #[Route('/piece/{id}/show', name: 'piece_show')]
    public function show(Piece $piece): Response
    {
        $deleteForm = $this->createDeleteForm($piece);

        return $this->render('piece/show.html.twig', [
            'piece' => $piece,
            'delete_form' => $deleteForm,
        ]);
    }

    #[Route('/piece/{id}/edit', name: 'piece_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Piece $piece, EntityManagerInterface $em): Response
    {
        $deleteForm = $this->createDeleteForm($piece);
        $editForm = $this->createForm(\App\Form\PieceType::class, $piece);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->flush();

            return $this->redirectToRoute('piece_show', ['id' => $piece->getId()]);
        }

        return $this->render('piece/edit.html.twig', [
            'piece' => $piece,
            'edit_form' => $editForm,
            'delete_form' => $deleteForm,
        ]);
    }

    #[Route('/piece/{id}/update', name: 'piece_update')]
    public function update(Request $request, Piece $piece, EntityManagerInterface $em): Response
    {
        if ($states = $request->query->get('states')) {
            foreach ($request->query->all('states') as $state => $action) {
                if ($action == -1) {
                    $piece->removeState($state);
                } else {
                    $piece->addState($state);
                }
            }
        }
        if ($location = $request->query->get('location')) {
            $piece->setLocation($location);
        }
        $em->flush();

        if ($return_path = $request->query->get('return_path')) {
            return $this->redirect($return_path);
        } else {
            return new Response(null, Response::HTTP_NO_CONTENT);
        }

    }

    #[Route('/', name: 'piece_delete', methods: ['DELETE'])]
    public function delete(Request $request, Piece $piece, EntityManagerInterface $em): Response
    {
        $form = $this->createDeleteForm($piece);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->remove($piece);
            $em->flush();
        }

        return $this->redirectToRoute('piece_index');
    }

    private function createDeleteForm(Piece $piece): Form
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('piece_delete', ['id' => $piece->getId()]))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    #[Route('/piece/duplicates/{master}/{duplicate}', methods: ['GET', 'POST'])]
    public function duplicates(Request $request, Piece $master, Piece $duplicate, TranslatorInterface $translator, EntityManagerInterface $em): Response
    {
        $masterForm = $this->createForm(\App\Form\PieceType::class, $master);
        $masterForm->add('concerts', EntityType::class, [
            'class' => Concert::class,
            'choice_label' => 'name',
            'multiple' => true,
        ]);
        $masterForm->handleRequest($request);

        if ($masterForm->isSubmitted() && $masterForm->isValid()) {
            $em->remove($duplicate);
            $em->flush();
            $this->addFlash(
                'success',
                $translator->trans(
                    'app.flash.success.duplicates.piece'
                )
            );
            return $this->redirectToRoute('piece_show', ['id' => $master->getId()]);
        }

        $duplicateForm = $this->createForm(\App\Form\PieceType::class, $duplicate);
        $duplicateForm->add('concerts', EntityType::class, [
            'class' => Concert::class,
            'choice_label' => 'name',
            'multiple' => true,
        ]);

        return $this->render('piece/duplicates.html.twig', [
            'piece' => $master,
            'master_form' => $masterForm,
            'duplicate_form' => $duplicateForm,
        ]);
    }

    #[Route('/suisa', name: 'piece_suisa', methods: ['GET', 'POST'])]
    public function suisa(Request $request, PieceRepository $pieceRepository): Response
    {
        if (!$request->query->get('start') || !$request->query->get('end')) {
            $today = new \DateTime();
            if ($today->format('n') < 2) {
                $start = new \DateTimeImmutable('last year January 1st');
            } else {
                $start = new \DateTimeImmutable('January 1st');
            }
            $end = $start->modify('+1 year -1 second');
        } else {
            $start = new \DateTimeImmutable($request->query->get('start'));
            $end = new \DateTimeImmutable($request->query->get('end'));
        }
        $pieces = $pieceRepository->findForSuisa($start, $end);

        return $this->render('piece/suisa.html.twig', [
            'pieces' => $pieces,
            'start'  => $start,
            'end'    => $end,
        ]);
    }
}