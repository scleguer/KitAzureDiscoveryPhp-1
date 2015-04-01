<?php

namespace Raspberry\HomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('RaspberryHomeBundle:Default:index.html.twig');
    }
    
    public function captureAction() 
    {
        return $this->render('RaspberryHomeBundle:Default:capture.html.twig');
    }
}
