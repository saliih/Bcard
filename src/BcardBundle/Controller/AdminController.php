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
        $path = $this->get('kernel')->getRootDir() . '/../web/uploads/';

        preg_match( '/width="([^"]*)"/i', file_get_contents($path.$recto), $arraywidth ) ;
        preg_match( '/height="([^"]*)"/i', file_get_contents($path.$recto), $arrayheight ) ;
        $unit = "mm";
        $height = 55;
        $width = 85;
        if(isset($arraywidth[1])){
            $width =(float) $arraywidth[1];
            $unit = "pt";
        }
        if(isset($arrayheight[1])){
            $height =(float) $arrayheight[1];
            $unit = "pt";
        }
        echo "<pre>";
        print_r(array($height,$width,$unit));
        exit;

        $pdf = $this->get("white_october.tcpdf")->create('vertical', $unit, array($width, $height), true, 'UTF-8', false);


        $pdf->SetMargins(40, 0, 40, false);
        $pdf->SetAutoPageBreak(true, 0);
        $pdf->setFontSubsetting(false);
        $pdf->AddPage();
        $pdf->ImageSVG($path .$recto, 0, 0, $width, $height);
        if($verso!="") {
            preg_match( '/width="([^"]*)"/i', file_get_contents($path.$verso), $arraywidth ) ;
            preg_match( '/width="([^"]*)"/i', file_get_contents($path.$verso), $arrayheight ) ;
            $height = 55;
            $width = 85;
            if(isset($arraywidth[1])){
                $width = (float) $arraywidth[1];
            }
            if(isset($arrayheight[1])){
                $height = (float) $arrayheight[1];
            }

            $pdf->AddPage();
            $pdf->ImageSVG($path . $verso, 0, 0, $width, $height);
        }
        $filename = 'caret_visite';
        $pdf->Output($filename . ".pdf", 'I'); // This will output the PDF as a response directly


    }
}
