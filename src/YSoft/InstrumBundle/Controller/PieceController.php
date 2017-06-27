<?php

namespace YSoft\InstrumBundle\Controller;

use YSoft\InstrumBundle\Entity\Piece;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use YSoft\InstrumBundle\Entity\Person;
use YSoft\InstrumBundle\Entity\Publisher;

/**
 * Piece controller.
 *
 */
class PieceController extends Controller
{
    /**
     * Lists all piece entities.
     *
     */
    public function indexAction(Request $request)
    {
        if (@$request->query->get('q')['id']) {
            return $this->redirectToRoute('piece_show', array('id' => $request->query->get('q')['id']));
        }

        $em = $this->getDoctrine()->getManager();

        $pieces = $em->getRepository('YSoftInstrumBundle:Piece')->searchBy(
            $request->query->get('q', array()),
            array(
                $request->query->get('field', 'id') => $request->query->get('direction', 'asc'),
            ),
            $this->getParameter('paginate.per_page'),
            ($request->query->get('page', 1) - 1) * $this->getParameter('paginate.per_page')
        );

        return $this->render('piece/index.html.twig', array(
            'pieces' => $pieces,
            'nbPages' => max(ceil($pieces->count() / $this->getParameter('paginate.per_page')), 1),
        ));
    }

    /**
     * Creates a new piece entity.
     *
     */
    public function newAction(Request $request)
    {
        $piece = new Piece();
        $form = $this->createForm('YSoft\InstrumBundle\Form\PieceType', $piece);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($piece);
            $em->flush();

            $this->addFlash(
                'success',
                $this->get('translator')->trans(
                    'ysoft.instrum.flash.success.creation.piece',
                    array(
                        '%id%'   => $piece->getId(),
                        '%name%' => $piece->getName(),
                    )
                )
            );
            return $this->redirectToRoute('piece_new');
        }

        return $this->render('piece/new.html.twig', array(
            'piece' => $piece,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a piece entity.
     *
     */
    public function showAction(Piece $piece)
    {
        $deleteForm = $this->createDeleteForm($piece);

        return $this->render('piece/show.html.twig', array(
            'piece' => $piece,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing piece entity.
     *
     */
    public function editAction(Request $request, Piece $piece)
    {
        $deleteForm = $this->createDeleteForm($piece);
        $editForm = $this->createForm('YSoft\InstrumBundle\Form\PieceType', $piece);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('piece_edit', array('id' => $piece->getId()));
        }

        return $this->render('piece/edit.html.twig', array(
            'piece' => $piece,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a piece entity.
     *
     */
    public function deleteAction(Request $request, Piece $piece)
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
            ->setAction($this->generateUrl('piece_delete', array('id' => $piece->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    public function importAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $sizeRepository = $em->getRepository('YSoftInstrumBundle:Size');
        $personRepository = $em->getRepository('YSoftInstrumBundle:Person');
        $publisherRepository = $em->getRepository('YSoftInstrumBundle:Publisher');
        if ($file = $request->files->get('file')) {

            $file = $file->openFile();
            $piece = new Piece();
            $previousLineId = null;
            ini_set('max_execution_time', 0);
            while ($line = $file->fgetcsv(';')) {
                if ($line[0] != $previousLineId) {
                    if ($piece->getName()) {
                        try {
                            $em->persist($piece);
                            $em->flush();
                        } catch (\Exception $e) {
                            dump($piece);
                            throw $e;
                        }
                        $piece = new Piece();
                    }
                    $piece
                        ->setName($line[4])
                        ->setYear(ctype_digit($line[16]) ? $line[16] : null)
                        ->setReference(in_array($line[17], ['-', '', ' ']) ? null : $line[17]);
                    $previousLineId = $line[0];
                    if (!preg_match('`^traduction :`i', $line[14])) {
                        $piece->setNote($line[14]);
                    }
                    switch ($line[6]) {
                        case 'A4':
                        case 'A5':
                            $size = $sizeRepository->find($line[6]);
                            break;
                        case 'Carnet de marche':
                            $size = $sizeRepository->find('A5-');
                            break;
                        default:
                            if (ctype_digit($line[7]) && ctype_digit($line[8])) {
                                if ($line[7] > $line[8]) {
                                    $size = $sizeRepository->findByDimension($line[8] * 10, $line[7] * 10);
                                } else {
                                    $size = $sizeRepository->findByDimension($line[7] * 10, $line[8] * 10);
                                }
                            } else {
                                $size = null;
                            }
                            break;
                    }
                    $piece->setSize($size);
                    if (!$publisher = $publisherRepository->findOneBy(array('name' => $line[15]))) {
                        $publisher = (new Publisher())
                            ->setName($line[15]);
                        $em->persist($publisher);
                        $em->flush();
                    }
                    $piece->setPublisher($publisher);
                    $piece->setStatus();
                } else if ($line[4] != $piece->getName()) {
                    $piece->setTranslation($line[4]);
                }
                $composer = $personRepository->findOneBy(array('firstName' => $line[3], 'lastName' => $line[2]));
                if (null == $composer && ($line[3] || $line[2])) {
                    $composer = (new Person())
                        ->setFirstName($line[3])
                        ->setLastName($line[2]);
                    $em->persist($composer);
                    $em->flush();
                }
                if ($composer && !$piece->getComposers()->contains($composer)) {
                    $piece->addComposer($composer);
                }
                $arranger = $personRepository->findOneBy(array('firstName' => '', 'lastName' => $line[5]));
                if (null == $arranger && $line[5]) {
                    $arranger = (new Person())
                        ->setFirstName('')
                        ->setLastName($line[5]);
                    $em->persist($arranger);
                    $em->flush();
                }
                if ($arranger && !$piece->getArrangers()->contains($arranger)) {
                    $piece->addArranger($arranger);
                }
            }
            $em->persist($piece);
            $em->flush();
        }

        return $this->render('piece/import.html.twig');
    }
}