<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * TODO: should be removed
     */
    public function indexAction(Request $request)
    {
        return $this->redirectToRoute('dittto_recognition_dashboard');
    }
}
