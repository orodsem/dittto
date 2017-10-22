<?php

namespace Dittto\RecognitionBundle\Controller;

use Dittto\RecognitionBundle\Entity\Recognition;
use Dittto\RecognitionBundle\Form\RecognitionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function dashboardAction()
    {
        return $this->render('DitttoRecognitionBundle:Default:dashboard.html.twig');
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function recogniseAction(Request $request)
    {
        // TODO: Here we need to get the logged in user as a sender
        $user = $this->getUser();

        $recognition = new Recognition();
        $recognition->setSender($user);
        $form = $this->createForm(RecognitionType::class, $recognition);

        //
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($recognition);
            $em->flush();
        }
        return $this->render('DitttoRecognitionBundle:Default:recogniseUser.html.twig',
            array('form' => $form->createView())
        );
    }
}
