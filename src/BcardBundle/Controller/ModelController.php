<?php

namespace BcardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ModelController extends Controller
{
    public function indexAction()
    {
        return $this->render('BcardBundle:Model:index.html.twig', array());
    }
}
