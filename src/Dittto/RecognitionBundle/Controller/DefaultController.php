<?php

namespace Dittto\RecognitionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function dashboardAction()
    {
        return $this->render('DitttoRecognitionBundle:Default:dashboard.html.twig');
    }
}
