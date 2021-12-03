<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Part controller
 */
class PartController extends AbstractController
{
    public function download(Request $request)
    {
        $ids = $request->query->get('ids', []);

        $result = null;
        $downloadName = 'default.bin';

        /** @var \App\Repository\PartRepository $repository */
        $repository = $this->getDoctrine()->getRepository(\App\Entity\Part::class);
        if (count($ids) == 1 && ($part = $repository->find($ids[0]))) {
            $result = $part->getUpload();
            $downloadName = $part->getPiece()->getId() . '-' . $part->getDownloadName();
        } else {
            $ids = array_unique($ids);
            $parts = $repository->findById($ids);
            if (!empty($parts)) {
                $downloadName = $parts[0]->getPiece()->getId() .' - ' . $parts[0]->getPiece()->getName();
                $zipTempPath = $this->getParameter('kernel.project_dir') . '/public/' . uniqid() . '.zip';
                $zip = new \ZipArchive();
                if ($zip->open($zipTempPath, \ZipArchive::CREATE) == true) {
                    foreach ($parts as $part) {
                        $zip->addFile($this->getParameter('kernel.project_dir') . '/public' .  $part->getDownloadPath(), $part->getPiece()->getId() . '-' . $part->getDownloadName());
                        $downloadName .= '-' . $part->getInstrument();
                    }
                    $zip->close();
                    $result = new \SplFileInfo($zipTempPath);
                }
            }
        }

        if ($result) {
            return $this->file($result, preg_replace('`♭`' , 'b', $downloadName));
        } else {
            return new \Symfony\Component\HttpFoundation\Response($result, \Symfony\Component\HttpFoundation\Response::HTTP_NO_CONTENT);
        }
    }
}