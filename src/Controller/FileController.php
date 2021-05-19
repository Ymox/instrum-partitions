<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Ajax upload controller.
 *
 */
class FileController extends AbstractController
{
    public function ajaxUpload(Request $request, \App\Service\FileUploader $uploader)
    {
        $file = $request->files->get('file');
        $fileName = $uploader->upload($file, $this->getParameter('upload_path'));
        $result = [
            'originalName' => $file->getClientOriginalName(),
            'fileName' => $fileName,
            'filePath' => $this->generateUrl('file_download', array('file' => ltrim($this->getParameter('download_path') . '/' . $fileName, '/'))),
            'target' => $request->get('target'),
        ];
        return $this->json($result);
    }

    public function download(Request $request, string $file)
    {
        $file = preg_replace('`(\.\./?)+`', '', $file);
        $filePath = $this->getParameter('kernel.project_dir') . '/web/' . $file;
        if (file_exists($filePath)) {
            return $this->file($filePath);
        } else {
            throw $this->createNotFoundException('File ' . $file . ' not found');
        }
    }
}