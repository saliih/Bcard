<?php

namespace BcardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {

        $templates = $this->getDoctrine()->getRepository('BcardBundle:Template')->findAll();

        return $this->render('BcardBundle:Default:index.html.twig',array('templates'=>$templates));
    }
}
