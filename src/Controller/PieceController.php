<?php

namespace App\Controller;

use App\Entity\Piece;
use App\Entity\Concert;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Piece controller.
 *
 */
class PieceController extends AbstractController
{
    /**
     * Lists all piece entities.
     *
     */
    public function index(Request $request)
    {
        $repo = $this->getDoctrine()->getManager()->getRepository(Piece::class);

        if (@$request->query->get('q')['id'] && ($piece = $repo->find($request->query->get('q')['id']))) {
            if ($piece->getWork()) {
                return $this->redirectToRoute('piece_show', ['id' => $piece->getWork()->getId()]);
            } else {
                return $this->redirectToRoute('piece_show', ['id' => $piece->getId()]);
            }
        }

        $pieces = $repo->searchBy(
            $request->query->get('q', []),
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

    /**
     * Creates a new piece entity.
     *
     */
    public function new(Request $request, TranslatorInterface $translator)
    {
        $piece = new Piece();
        $piece
            ->setLocation(Piece::LOCATION_SHELF)
            ->setStates(0)
        ;
        $form = $this->createForm(\App\Form\PieceType::class, $piece);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
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
            'form' => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a piece entity.
     *
     */
    public function show(Piece $piece)
    {
        $deleteForm = $this->createDeleteForm($piece);

        return $this->render('piece/show.html.twig', [
            'piece' => $piece,
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing piece entity.
     *
     */
    public function edit(Request $request, Piece $piece)
    {
        $deleteForm = $this->createDeleteForm($piece);
        $editForm = $this->createForm(\App\Form\PieceType::class, $piece);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('piece_show', ['id' => $piece->getId()]);
        }

        return $this->render('piece/edit.html.twig', [
            'piece' => $piece,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Quick update for pieces (states or location)
     */
    public function update(Request $request, Piece $piece)
    {
        foreach ($request->query->get('states', []) as $state => $action) {
            if ($action == -1) {
                $piece->removeState($state);
            } else {
                $piece->addState($state);
            }
        }
        if ($location = $request->query->get('location')) {
            $piece->setLocation($location);
        }
        $this->getDoctrine()->getManager()->flush();

        if ($return_path = $request->query->get('return_path')) {
            return $this->redirect($return_path);
        } else {
            return new Response(null, Response::HTTP_NO_CONTENT);
        }

    }

    /**
     * Deletes a piece entity.
     *
     */
    public function delete(Request $request, Piece $piece)
    {
        $form = $this->createDeleteForm($piece);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($piece);
            $em->flush();
        }

        return $this->redirectToRoute('piece_index');
    }

    /**
     * Creates a form to delete a piece entity.
     *
     * @param Piece $piece The piece entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Piece $piece)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('piece_delete', ['id' => $piece->getId()]))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * Allows to handle duplicates in database
     *
     * @param Request $request
     * @param Piece $master The piece that will be kept
     * @param Piece $duplicate The piece that will be deleted
     */
    public function duplicates(Request $request, Piece $master, Piece $duplicate, TranslatorInterface $translator)
    {
        $masterForm = $this->createForm(\App\Form\PieceType::class, $master);
        $masterForm->add('concerts', EntityType::class, [
            'class' => Concert::class,
            'choice_label' => 'name',
            'multiple' => true,
        ]);
        $masterForm->handleRequest($request);

        if ($masterForm->isSubmitted() && $masterForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
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
            'master_form' => $masterForm->createView(),
            'duplicate_form' => $duplicateForm->createView(),
        ]);
    }

    public function suisa(Request $request)
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
        $pieces = $this->getDoctrine()->getManager()->getRepository(Piece::class)
            ->findForSuisa($start, $end);

        return $this->render('piece/suisa.html.twig', [
            'pieces' => $pieces,
            'start'  => $start,
            'end'    => $end,
        ]);
    }
}