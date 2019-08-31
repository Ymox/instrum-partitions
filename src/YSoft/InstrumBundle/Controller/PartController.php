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
    public function downloadAction(
        Request $request,
        \YSoft\InstrumBundle\Entity\Part $part
    ) {
        return new \Symfony\Component\HttpFoundation\BinaryFileResponse($part->getUpload());
    }
}