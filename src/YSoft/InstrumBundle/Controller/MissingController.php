<?php

namespace YSoft\InstrumBundle\Controller;

use YSoft\InstrumBundle\Entity\Missing;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


/**
 * Missing controller.
 *
 */
class MissingController extends Controller
{
    /**
     * Lists all missing entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $missings = $em->getRepository('YSoftInstrumBundle:Missing')->findAll();

        return $this->render('missing/index.html.twig', array(
            'missings' => $missings,
        ));
    }

    /**
     * Finds and displays a missing entity.
     *
     */
    public function showAction(Missing $missing)
    {

        return $this->render('missing/show.html.twig', array(
            'missing' => $missing,
        ));
    }
}
