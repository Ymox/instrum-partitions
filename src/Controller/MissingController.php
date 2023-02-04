<?php

namespace App\Controller;

use App\Entity\Missing;
use App\Repository\MissingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/missing', name: 'missing_')]
class MissingController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(MissingRepository $missingRepository): Response
    {
        return $this->render('missing/index.html.twig', [
            'missings' => $missingRepository->findAll(),
        ]);
    }

    #[Route('/{id}/delete', name: 'delete', methods: ['DELETE'])]
    public function delete(Request $request, Missing $missing, EntityManagerInterface $em): Response
    {
        $form = $this->createDeleteForm($missing);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->remove($missing);
            $em->flush();
        }

        return $this->redirectToRoute('missing_index');
    }

    private function createDeleteForm(Missing $missing): Form
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('missing_delete', ['id' => $missing->getId()]))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}