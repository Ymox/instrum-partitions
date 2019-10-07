<?php

namespace YSoft\InstrumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Ajax upload controller.
 *
 */
class PartController extends Controller
{
    public function downloadAction(Request $request)
    {
        $ids = $request->query->get('ids', []);

        $result = null;
        $downloadName = 'default.bin';

        /** @var \Ysoft\InstrumBundle\Repository\PartRepository $repository */
        $repository = $this->getDoctrine()->getRepository(\YSoft\InstrumBundle\Entity\Part::class);
        if (count($ids) == 1 && ($part = $repository->find($ids[0]))) {
            $result = $part->getUpload();
            $downloadName = $part->getPiece()->getId() . '-' . $part->getDownloadName();
        } else {
            $ids = array_unique($ids);
            sort($ids);
            $parts = $repository->findById($ids);
            if (!empty($parts)) {
                $downloadName = $parts[0]->getPiece()->getId() .' - ' . $parts[0]->getPiece()->getName();
                $zipTempPath = $this->getParameter('kernel.project_dir') . '/web/' . uniqid() . '.zip';
                $zip = new \ZipArchive();
                if ($zip->open($zipTempPath, \ZipArchive::CREATE) == true) {
                    foreach ($parts as $part) {
                        $zip->addFile($this->getParameter('kernel.project_dir') . '/web' .  $part->getDownloadPath(), $part->getPiece()->getId() . '-' . $part->getDownloadName());
                        $downloadName .= '-' . $part->getInstrument();
                    }
                    $zip->close();
                    $result = new \SplFileInfo($zipTempPath);
                }
            }
        }

        if ($result) {
            $response = new \Symfony\Component\HttpFoundation\BinaryFileResponse($result);
            $response->setContentDisposition(
                \Symfony\Component\HttpFoundation\ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                $downloadName
            );
            return $response;
        } else {
            return new \Symfony\Component\HttpFoundation\Response($result, \Symfony\Component\HttpFoundation\Response::HTTP_NO_CONTENT);
        }
    }
}