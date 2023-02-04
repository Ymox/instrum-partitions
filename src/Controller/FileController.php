<?php

namespace App\Controller;

use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FileController extends AbstractController
{
    #[Route('/upload', name: 'ajax_upload', methods: ['POST'], condition: 'request.isXmlHttlRequest')]
    public function ajaxUpload(Request $request, FileUploader $uploader): Response
    {
        $file = $request->files->get('file');
        $fileName = $uploader->upload($file, $this->getParameter('upload_path'));
        $result = [
            'originalName' => $file->getClientOriginalName(),
            'fileName' => $fileName,
            'filePath' => $this->generateUrl('file_download', ['file' => ltrim($this->getParameter('download_path') . '/' . $fileName, '/')]),
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
}