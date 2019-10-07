<?php

namespace YSoft\InstrumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Ajax upload controller.
 *
 */
class FileController extends Controller
{
    public function ajaxUploadAction(Request $request, \YSoft\InstrumBundle\Service\FileUploader $uploader, \Symfony\Component\Routing\RouterInterface $router)
    {
        $file = $request->files->get('file');
        $fileName = $uploader->upload($file, $this->getParameter('upload_path'));
        $result = [
            'originalName' => $file->getClientOriginalName(),
            'fileName' => $fileName,
            'filePath' => $router->generate('y_soft_instrum_file_download', array('file' => ltrim($this->getParameter('download_path') . '/' . $fileName, '/'))),
            'target' => $request->get('target'),
        ];
        return $this->json($result);
    }

    public function downloadAction(Request $request, string $file)
    {
        $file = preg_replace('`(\.\./?)+`', '', $file);
        $filePath = $this->getParameter('kernel.project_dir') . '/web/' . $file;
        if (file_exists($filePath)) {
            return new \Symfony\Component\HttpFoundation\BinaryFileResponse($filePath);
        } else {
            throw $this->createNotFoundException('File ' . $file . ' not found');
        }
    }
}