<?php

namespace Dittto\RecognitionBundle\Controller;

use Dittto\RecognitionBundle\Entity\Recognition;
use Dittto\RecognitionBundle\Form\RecognitionType;
use Dittto\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function dashboardAction(Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();

        // number of recog. received by user
        $userRecognitions = $user->getRecognitions();
        $countUserRecognitions = count($userRecognitions);

        $em = $this->getDoctrine()->getManager();
        $allRecognitions = $em->getRepository('DitttoRecognitionBundle:Recognition')->findAll();
        // total number of recognitions
        $countAllRecognitions = count($allRecognitions);

        $userVsTotal = array(
            'countUserRecognitions' => $countUserRecognitions,
            'countAllRecognitions' => $countAllRecognitions,
            );

        return $this->render('DitttoRecognitionBundle:Default:dashboard.html.twig',
            array('userVsTotal' => json_encode($userVsTotal))
        );
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
