<?php

namespace App\Controller;

use App\Repository\PartRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/part', name: 'part_')]
class PartController extends AbstractController
{
    #[Route('/download', name: 'download')]
    public function download(Request $request, PartRepository $partRepository): Response
    {
        $ids = $request->query->has('ids') ? $request->query->all('ids') : [];

        $result = null;
        $downloadName = 'default.bin';

        if (count($ids) == 1 && ($part = $partRepository->find($ids[0]))) {
            $result = $part->getUpload();
            $downloadName = $part->getPiece()->getId() . '-' . $part->getDownloadName();
        } else {
            $ids = array_unique($ids);
            $parts = $partRepository->findById($ids);
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
            return new Response($result, Response::HTTP_NO_CONTENT);
        }
    }
}