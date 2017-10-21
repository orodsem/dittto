<?php

namespace Dittto\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('DitttoUserBundle:Default:index.html.twig');
    }
}
