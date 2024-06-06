<?php

namespace App\Controller;

use App\Entity\Part;
use App\Entity\Piece;
use App\Entity\Translatable\PartTranslatableMessage;
use App\Repository\PartRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Translation\TranslatorInterface;
use ZipStream\ZipStream;

#[Route('/part', name: 'part_')]
class PartController extends AbstractController
{
    #[Route('/download', name: 'download')]
    public function download(Request $request, PartRepository $partRepository, TranslatorInterface $translator): Response
    {
        $ids = $request->query->has('ids') ? $request->query->all('ids') : [];

        $result = null;
        $downloadName = 'default.bin';

        if (count($ids) == 1 && ($part = $partRepository->find($ids[0]))) {
            $extension = '.pdf';
            $result = $part->getUpload();
            $downloadName = (new PartTranslatableMessage($part))->trans($translator);
        } else {
            $extension = '.zip';
            $ids = array_unique($ids);
            $parts = $partRepository->findById($ids);
            if (!empty($parts)) {
                $downloadName = $parts[0]->getPiece()->getId() .' - ' . $parts[0]->getPiece()->getName();
                $zipTempPath = $this->getParameter('kernel.project_dir') . '/public/' . uniqid() . '.zip';
                $zip = new \ZipArchive();
                if ($zip->open($zipTempPath, \ZipArchive::CREATE) == true) {
                    foreach ($parts as $part) {
                        $zip->addFile(
                            $part->getDownloadPath(),
                            (new PartTranslatableMessage($part))->trans($translator) . ' .pdf'
                        );
                        $downloadName .= '-' . (new PartTranslatableMessage($part, translationKey: 'app.part.to_string.no_id'))->trans($translator);
                    }
                    $zip->close();
                    $result = new \SplFileInfo($zipTempPath);
                }
            }
        }

        if ($result) {
            return $this->file($result, $this->sanitizeName($downloadName) . $extension);
        } else {
            return new Response($result, Response::HTTP_NO_CONTENT);
        }
    }

    #[Route('/new', name: 'new')]
    public function new(Request $request, PartRepository $partRepository, TranslatorInterface $translator): Response
    {
        $part = new Part();
        if ($file = $request->query->get('file')) {
            $part->setFile($file);
        }

        $form = $this->createForm(\App\Form\PartType::class, $part, [
            'action' => $this->generateUrl('part_new')
        ]);
        $form
            ->add('piece', EntityType::class, [
                'class' => Piece::class,
                'choice_label' => 'name',
                'label_format' => 'app.fields.part.%name%.label',
                'placeholder' => 'app.fields.part.piece.placeholder',
                'attr' => [
                    'class' => 'searchable',
                ],
            ])
        ;
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $partRepository->save($part, true);

            if ($request->isXmlHttpRequest()) {
                return $this->render('success.html.twig', [
                    'message' => 'app.success.creation.part',
                ]);
            }
            $this->addFlash(
                'success',
                $translator->trans('app.flash.success.creation.part'),
            );
            return $this->redirectToRoute('piece_show', ['id' => $part->getPiece()->getId()]);
        }

        return $this->render('part/new.html.twig', [
            'part' => $part,
            'form' => $form,
        ]);
    }

    #[Route('/backup', name: 'backup')]
    public function backup(PartRepository $partRepository, TranslatorInterface $translator): Response
    {
        set_time_limit(0);

        $parts = $partRepository->findWithFile();
        if (!empty($parts)) {
            $response = new StreamedResponse(function() use($parts, $translator)
            {
                $zip = new ZipStream(
                    outputName: sprintf('Parts_backup_%s.zip', date('Y-m-d_Hi')),
                    defaultEnableZeroHeader: true,
                    contentType: 'application/zip',
                );

                foreach ($parts as $part) {
                    if (!is_file($fullFilePath = $part->getDownloadPath())) {
                        continue;
                    }
                    $zip->addFileFromPath(
                        $this->sanitizeName((new PartTranslatableMessage($part))->trans($translator)) . '-' . $part->getFile(),
                        $fullFilePath
                    );
                }

                $zip->finish();
            });
        } else {
            $response = new Response(null, Response::HTTP_NO_CONTENT);
        }

        return $response;
    }

    private function sanitizeName(string $name): string
    {
        return preg_replace(['` +`', '`\.`', '`♭`'] , ['_', '-', 'b'], $name);
    }
}