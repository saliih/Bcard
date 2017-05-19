<?php

namespace BcardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ModelController extends Controller
{
    public function indexAction()
    {
        return $this->render('BcardBundle:Model:index.html.twig', array());
    }

    public function generateFormAction(Request $request)
    {
        $type = $request->request->get('type');
        $data['id'] = $request->request->get('id');
        switch ($type) {
            case "rect":
                $extra = array(
                    "stroke" => $request->request->get('stroke'),
                    "fill" => $request->request->get('fill'),
                    //"x" =>$request->request->get('x'),
                    // "y" =>$request->request->get('y'),
                    //"width" =>$request->request->get('width'),
                    //"height" =>$request->request->get('height'),
                );
                break;
            case "image":
                $extra = array("file" => "file");
                break;
            case "text":
                $extra = array(
                    "text" => $request->request->get('text'),
                    "stroke" => $request->request->get('stroke'),
                    "fill" => $request->request->get('fill'),
                    "fontfamily" => $request->request->get('fontfamily'),
                    "fontsize" => $request->request->get('fontsize'),
                );
                break;
            case "circle":
                $extra = array(
                    "stroke" => $request->request->get('stroke'),
                    "fill" => $request->request->get('fill'),
                    "strokewidth" => $request->request->get('strokewidth'),
                );
                break;
            case "path":
                $extra = array(
                    "stroke" => $request->request->get('stroke'),
                    "fill" => $request->request->get('fill'),
                    "strokewidth" => $request->request->get('strokewidth'),
                );
                break;
        }
        $html = "";
        $data = array_merge($data, $extra);
        foreach ($data as $key => $value) {
            if ($key == 'id') continue;
            else if ($key == "stroke") {
                $html .= $this->renderView("BcardBundle:Fields:color.html.twig",
                    array(
                        'title' => "Couleur de trait",
                        'value' => $value,
                        'key' => $key,
                        'id' => $data['id']
                    ));
            }
            else if ($key == "fill") {
                $html .= $this->renderView("BcardBundle:Fields:color.html.twig",
                    array(
                        'title' => "Couleur ",
                        'value' => $value,
                        'key' => $key,
                        'id' => $data['id']
                    ));
            }
            else if ($key == "fontfamily") {
                $html .= $this->renderView("BcardBundle:Fields:fontfamily.html.twig",
                    array(
                        'title' => "Police ",
                        'value' => $value,
                        'key' => $key,
                        'id' => $data['id']
                    ));
            }
            else if ($key == "fontsize") {
                $html .= $this->renderView("BcardBundle:Fields:fontsize.html.twig",
                    array(
                        'title' => "Taille de police ",
                        'value' => $value,
                        'key' => $key,
                        'id' => $data['id']
                    ));
            }
            else if ($key == "text") {
                $html .= $this->renderView("BcardBundle:Fields:text.html.twig",
                    array(
                        'title' => "Text ",
                        'value' => $value,
                        'key' => $key,
                        'id' => $data['id']
                    ));
            }
            else if ($key == "file") {
                $html .= $this->renderView("BcardBundle:Fields:file.html.twig",
                    array(
                        'title' => "Logo",
                        'key' => $key,
                        'id' => $data['id']
                    ));
            }
        }
        return new Response($html);
    }

    public function uploadfileAction(Request $request){
        $file = $request->files->get('filelogo');
        $time = time()+rand(1,61561). $file->getClientOriginalExtension();
        $file->move(
            $this->getParameter('kernel.root_dir') . "/../web/uploads/",
            $time
        );
        $filename =  $this->getParameter('kernel.root_dir') . "/../web/uploads/".$time;
        $type = pathinfo($filename, PATHINFO_EXTENSION);
        $data = file_get_contents($filename);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        return new Response($base64);

    }
}
