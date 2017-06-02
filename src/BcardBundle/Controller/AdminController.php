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
        $path = $this->get('kernel')->getRootDir() . '/../web/uploads/svg/';
        file_put_contents($path . "recto" . $id . ".svg", $recto);
        file_put_contents($path . "verso" . $id . ".svg", $verso);

        $pdf = $this->get("white_october.tcpdf")->create('vertical', "mm", array(85, 55), true, 'UTF-8', false);


        $pdf->SetMargins(40, 0, 40, false);
        $pdf->SetAutoPageBreak(true, 0);
        $pdf->setFontSubsetting(false);
        $pdf->AddPage();
        $pdf->ImageSVG($path . "recto" . $id . ".svg", 0, 0, 85, 55);
        $pdf->AddPage();
        $pdf->ImageSVG($path . "verso" . $id . ".svg", 0, 0, 85, 55);

        $filename = 'ourcodeworld_pdf_demo';
        $pdf->Output($filename . ".pdf", 'I'); // This will output the PDF as a response directly

        exit;
    }
}
