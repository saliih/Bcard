<?php

namespace BcardBundle\Controller;

use BcardBundle\Entity\Invoice;
use BcardBundle\Entity\Template;
use BcardBundle\Form\InvoiceType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ModelController extends Controller
{
    public function indexAction(Template $template, Request $request)
    {
        $form = $this->createForm(new InvoiceType(), new Invoice());

        return $this->render('BcardBundle:Model:index.html.twig', array("template" => $template,
            "form" => $form->createView()
        ));
    }
    public function uploadlogoAction(Request $request){
        $file = $request->files->get('upload-file-selector');
        if($file ==""){
            $file = $request->files->get('upload-file-selector-background');
        }
        $time = time() + rand(1, 61561) .".". $file->getClientOriginalExtension();
        $file->move(
            $this->getParameter('kernel.root_dir') . "/../web/uploads/",
            $time
        );
        $filename = $this->getParameter('kernel.root_dir') . "/../web/uploads/" . $time;
        $info = getimagesize($filename);
        list($x, $y) = $info;
        $type = pathinfo($filename, PATHINFO_EXTENSION);
        $data = file_get_contents($filename);
        //,'base64'=>base64_encode($data)
        return new JsonResponse(array("type"=>$type,"width"=>$x,"height"=>$y,"url"=>"/bcard/web/uploads/".$time));
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        return new JsonResponse(array('base64'=>$base64,"url"=>$filename));
    }

    public function submitInvoiceAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $invoice = new Invoice();
        $simpleData = $request->request->get('bcardbundle_invoice');
        $invoice->setEmail($simpleData['email']);
        $invoice->setName($simpleData['name']);
        $invoice->setAdresse($simpleData['adresse']);
        $invoice->setTel($simpleData['tel']);
        $recto = time()."_recto.svg";
        file_put_contents($this->get('kernel')->getRootDir() . '/../web/uploads/'.$recto,$request->request->get('recto'));
        $invoice->setRecto($recto);
        if(strlen($request->request->get('verso'))>0){
            $verso = time()."_verso.svg";
            file_put_contents($this->get('kernel')->getRootDir() . '/../web/uploads/'.$verso,$request->request->get('recto'));
            $invoice->setVerso($verso);
        }
        $em->persist($invoice);
        $em->flush();
        return new JsonResponse(array('success'=>true));
    }

    public function generateFormAction(Request $request)
    {
        $type = $request->request->get('type');
        $extra = array();
        switch ($type) {
            case "rect":
                $extra = array(
                    "fill" => $request->request->get('fill'),
                    //"stroke" => $request->request->get('stroke'),
                    //"x" => $request->request->get('x'),
                    //"y" => $request->request->get('y'),
                    //"width" =>$request->request->get('width'),
                    //"height" =>$request->request->get('height'),
                );
                break;
            case "image":
                $extra = array("file" => "file",
                    'x'=>$request->request->get('x'),
                    'y'=>$request->request->get('y'),
                    "width" =>$request->request->get('width'),
                    //"height" =>$request->request->get('height'),
                );
                break;
            case "text":
                $extra = array(
                    "text" => $request->request->get('text'),
                    "fill" => $request->request->get('fill'),
                   // "stroke" => $request->request->get('stroke'),
                    "fontfamily" => $request->request->get('fontfamily'),
                    "fontsize" => $request->request->get('fontsize'),
                );
                break;
            case "circle":
                $extra = array(
                    "fill" => $request->request->get('fill'),
                    //"stroke" => $request->request->get('stroke'),
                    //"strokewidth" => $request->request->get('strokewidth'),
                );
                break;
            case "path":
                $extra = array(
                    "fill" => $request->request->get('fill'),
                   // "stroke" => $request->request->get('stroke'),
                   // "strokewidth" => $request->request->get('strokewidth'),
                );
                break;
        }


        $html = "";
        foreach ($extra as $key => $value) {
            if ($key == 'id') continue;
            else if ($key == "stroke") {
                $html .= $this->renderView("BcardBundle:Fields:color.html.twig",
                    array(
                        'title' => "Couleur de trait",
                        'value' => $value,
                        'key' => $key,
                    ));
            } else if ($key == "fill") {
                $html .= $this->renderView("BcardBundle:Fields:color.html.twig",
                    array(
                        'title' => "Couleur ",
                        'value' => $value,
                        'key' => $key,
                    ));
            } else if ($key == "fontfamily") {
                $html .= $this->renderView("BcardBundle:Fields:fontfamily.html.twig",
                    array(
                        'title' => "Police ",
                        'value' => $value,
                        'key' => $key,
                    ));
            } else if ($key == "fontsize") {
                $html .= $this->renderView("BcardBundle:Fields:fontsize.html.twig",
                    array(
                        'title' => "Taille de police ",
                        'value' => $value,
                        'key' => $key,
                    ));
            } else if ($key == "text") {
                $html .= $this->renderView("BcardBundle:Fields:text.html.twig",
                    array(
                        'title' => "Text ",
                        'value' => $value,
                        'key' => $key,
                    ));
            } else if (in_array($key, array("x", "y"))) {
                $html .= $this->renderView("BcardBundle:Fields:number.html.twig",
                    array(
                        'title' => "coordonnées ",
                        'value' => $value,
                        'key' => $key,
                    ));
            } else if (in_array($key, array("width", "height"))) {
                $html .= $this->renderView("BcardBundle:Fields:range.html.twig",
                    array(
                        'title' => "largeur",
                        'value' => $value,
                        'key' => $key,
                    ));
            } else if ($key == "file") {
                $html .= $this->renderView("BcardBundle:Fields:file.html.twig",
                    array(
                        'title' => "Logo",
                        'key' => $key,
                    ));
            }
        }
        return new Response($html);
    }

    public function uploadfileAction(Request $request)
    {
        $file = $request->files->get('filelogo');
        $time = time() + rand(1, 61561) . $file->getClientOriginalExtension();
        $file->move(
            $this->getParameter('kernel.root_dir') . "/../web/uploads/",
            $time
        );
        $filename = $this->getParameter('kernel.root_dir') . "/../web/uploads/" . $time;
        $type = pathinfo($filename, PATHINFO_EXTENSION);
        $data = file_get_contents($filename);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        return new Response($base64);

    }
}
