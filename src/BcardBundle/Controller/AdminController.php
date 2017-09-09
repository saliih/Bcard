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
        /*echo "<pre>";
        print_r(array($height,$width,$unit));
        exit;
*/
        $pdf = $this->get("white_october.tcpdf")->create('vertical', $unit, array($width, $height), true, 'UTF-8', false);


        $pdf->SetMargins(40, 0, 40, false);
        $pdf->SetAutoPageBreak(true, 0);
        $pdf->setFontSubsetting(false);
        $pdf->AddPage();

        $pdf->ImageSVG($path .$recto, 0, 0, $width, $height);
        $this->image($pdf,$path .$recto);
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

            $pdf->ImageSVG($path . $verso, 0, 0, $width, $height, '','', '', 0, true);
            $this->image($pdf,$path .$verso);
        }
        $filename = 'caret_visite';
        $pdf->Output($filename . ".pdf", 'I'); // This will output the PDF as a response directly

    }
    private function image($pdf,$svg){
        $contentrecto =  file_get_contents(     $svg);
        preg_match_all('/<image[^>]+>/i',$contentrecto, $result);
        foreach ($result as $image){
            $images = array();
            preg_match_all('/(height|width|href|x|y|style)=("[^"]*")/i',$image[0], $data);
            foreach ($data[1] as $key=>$element){
                $images[$element] = substr($data[2][$key],1,-1);
            }
            //echo "<pre>";print_r($images);
            $pdf->Image($this->get('kernel')->getRootDir() . '/../web'.$images['href'],(int) $images['x'],10,$images['width'],$images['height']);
            //$pdf->Image($this->get('kernel')->getRootDir() . '/../web'.substr($images['href'],1,-1),($images['x']/4.16),($images['y']/4.16),$images['width'],$images['height']);

        }


    }
}
