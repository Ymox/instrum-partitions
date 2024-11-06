<?php

namespace App\Controller;

use App\Entity\Band;
use App\Repository\BandRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/band', name: 'band_')]
class BandController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(Request $request, BandRepository $bandRepository): Response
    {
        $bands = $bandRepository->paginateBy([], ['name' => 'ASC'], $this->getParameter('paginate.per_page'), ($request->query->get('page', 1) - 1) * $this->getParameter('paginate.per_page'));
        
        return $this->render('band/index.html.twig', [
            'bands' => $bands,
            'nbPages' => max(ceil($bands->count() / $this->getParameter('paginate.per_page')), 1),
        ]);
    }
    
    #[Route('/', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $band = new Band();
        $form = $this->createForm(\App\Form\BandType::class, $band, [
            'action' => $this->generateUrl('band_new'),
        ]);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
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
            'form' => $form,
        ]);
    }
    
    #[Route('/{id}/show', name: 'show')]
    public function show(#[MapEntity] Band $band): Response
    {
        $deleteForm = $this->createDeleteForm($band);
        
        return $this->render('band/show.html.twig', [
            'band' => $band,
            'delete_form' => $deleteForm,
        ]);
    }
    
    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, #[MapEntity] Band $band, EntityManagerInterface $em): Response
    {
        $deleteForm = $this->createDeleteForm($band);
        $editForm = $this->createForm(\App\Form\BandType::class, $band);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em->flush();

            return $this->redirectToRoute('band_edit', ['id' => $band->getId()]);
        }

        return $this->render('band/edit.html.twig', [
            'band' => $band,
            'edit_form' => $editForm,
            'delete_form' => $deleteForm,
        ]);
    }

    #[Route('/{id}/delete', name: 'delete', methods: ['DELETE'])]
    public function delete(Request $request, #[MapEntity] Band $band, EntityManagerInterface $em): Response
    {
        $form = $this->createDeleteForm($band);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->remove($band);
            $em->flush();
        }

        return $this->redirectToRoute('band_index');
    }

    private function createDeleteForm(Band $band): Form
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('band_delete', ['id' => $band->getId()]))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
