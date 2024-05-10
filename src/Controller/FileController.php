<?php

namespace App\Controller;

use App\Repository\PartRepository;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class FileController extends AbstractController
{
    #[Route('/upload', name: 'ajax_upload', methods: ['POST'], condition: 'request.isXmlHttpRequest()')]
    public function ajaxUpload(Request $request, FileUploader $uploader, string $uploadPath, string $downloadPath): Response
    {
        $file = $request->files->get('file');
        $fileName = $uploader->upload($file, $uploadPath);
        $result = [
            'originalName' => $file->getClientOriginalName(),
            'fileName' => $fileName,
            'filePath' => $this->generateUrl('file_download', ['file' => ltrim($downloadPath . '/' . $fileName, '/')]),
            'target' => $request->get('target'),
        ];

        return $this->json($result);
    }

    #[Route('/download/{file}', name: 'file_download', requirements: ['file' => '.+'])]
    public function download(Request $request, string $file): Response
    {
        $file = preg_replace('`(\.\./?)+`', '', $file);
        $filePath = $this->getParameter('kernel.project_dir') . '/public/' . $file;

        if (file_exists($filePath)) {
            return $this->file($filePath);
        } else {
            throw $this->createNotFoundException('File ' . $file . ' not found');
        }
    }

    #[Route('/clean', name: 'file_clean')]
    public function clean(Request $request, PartRepository $partRepository, string $uploadPath, string $downloadPath)
    {
        $existingFiles = array_diff(scandir($uploadPath), ['.', '..', '.gitignore', '.htaccess']);
        $missingFiles = $partRepository->findMissingFile($existingFiles);
        $uselessFiles = array_diff($existingFiles, $partRepository->getFiles());
        
        return $this->render('file_clean.html.twig', [
            'downloadPath' => $downloadPath,
            'missingFiles' => $missingFiles,
            'uselessFiles' => $uselessFiles,
        ]);
    }

    #[Route('/delete/{file}', name: 'file_delete', methods: ['DELETE'], requirements: ['file' => '.+'])]
    public function delete(Request $request, string $file, TranslatorInterface $translator): Response
    {
        $file = preg_replace('`(\.\./?)+`', '', $file);
        $filePath = $this->getParameter('kernel.project_dir') . '/public/' . $file;

        if (file_exists($filePath)) {
            if (unlink($filePath)) {
                $this->addFlash(
                    'success',
                    $translator->trans(
                        'app.flash.success.deletion.file',
                    )
                );
            } else {
                $this->addFlash(
                    'warning',
                    $translator->trans(
                        'app.flash.warning.deletion.file',
                    )
                );
            }
        } else {
            $this->addFlash(
                'danger',
                $translator->trans(
                    'app.flash.error.deletion.file',
                )
            );
        }

        return $this->redirectToRoute('file_clean', [], Response::HTTP_FOUND);
    }
}