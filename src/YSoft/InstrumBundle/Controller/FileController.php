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
    public function ajaxUploadAction(Request $request, \YSoft\InstrumBundle\Service\FileUploader $uploader)
    {
        $file = $request->files->get('file');
        $fileName = $uploader->upload($file, $this->getParameter('upload_path'));
        $result = [
            'originalName' => $file->getClientOriginalName(),
            'fileName' => $fileName,
            'filePath' => $this->getParameter('download_path') . '/' . $fileName,
            'target' => $request->get('target'),
        ];
        return $this->json($result);
    }
}