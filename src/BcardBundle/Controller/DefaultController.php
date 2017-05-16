<?php

namespace BcardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('BcardBundle:Default:index.html.twig');
    }
}
