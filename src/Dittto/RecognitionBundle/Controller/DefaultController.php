<?php

namespace Dittto\RecognitionBundle\Controller;

use Dittto\RecognitionBundle\Entity\Recognition;
use Dittto\RecognitionBundle\Entity\Repository\RecognitionRepository;
use Dittto\RecognitionBundle\Form\RecognitionType;
use Dittto\UserBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function dashboardAction(Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        // number of recog. received by user
        $totalReceivedByUser = count($user->getRecognitions());

        /** @var RecognitionRepository $recognitionRepo */
        $recognitionRepo = $em->getRepository('DitttoRecognitionBundle:Recognition');
        $totalSentByUser = $recognitionRepo->getTotalRecognitionSentByUserId($user->getId());

        // total number of recognitions
        $totalRecognitions = count($recognitionRepo->findAll());

        $userVsTotal = array(
            'totalSentByUser' => $totalSentByUser,
            'totalReceivedByUser' => $totalReceivedByUser,
            'totalRecognitions' => $totalRecognitions,
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
        $user = $this->getUser();

        $recognition = new Recognition();
        $form = $this->createForm(RecognitionType::class, $recognition);

        // update the recognition object with the submitted form
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $recognition->setSender($user);
            $em = $this->getDoctrine()->getManager();
            $em->persist($recognition);
            $em->flush();
        }
        return $this->render('DitttoRecognitionBundle:Default:recogniseUser.html.twig',
            array('form' => $form->createView())
        );
    }
}
