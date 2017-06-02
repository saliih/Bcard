<?php

namespace BcardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminController extends Controller
{
    public function generatePdfAction($id)
    {
        $invoice = $this->getDoctrine()->getRepository('BcardBundle:Invoice')->find($id);
        $recto = $invoice->getRecto();
        $verso = $invoice->getVerso();

        exit;
    }
}
