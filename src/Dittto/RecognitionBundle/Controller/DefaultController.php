<?php

namespace Dittto\RecognitionBundle\Controller;

use Dittto\RecognitionBundle\Entity\Recognition;
use Dittto\RecognitionBundle\Entity\RecognitionReceived;
use Dittto\RecognitionBundle\Entity\Repository\RecognitionReceivedRepository;
use Dittto\RecognitionBundle\Entity\Repository\RecognitionRepository;
use Dittto\RecognitionBundle\Form\RecognitionType;
use Dittto\UserBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function dashboardAction(Request $request)
    {
//        TODO: This should be a sevrice, chartGenerator, get an entity and return diff. reports about that entity!!
        /** @var User $user */
        $user = $this->getUser();

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        // number of recog. received by user
        /** @var RecognitionReceivedRepository $recognitionReceivedRepo */
        $recognitionReceivedRepo = $em->getRepository('DitttoRecognitionBundle:RecognitionReceived');
        $totalReceivedByUser = $recognitionReceivedRepo->getRecognitionReceivedByUserId($user->getId());

        // total number of recognitions "received" to cover 1 to M
        $totalRecognitions = count($recognitionReceivedRepo->findAll());

        /** @var RecognitionRepository $recognitionRepo */
        $recognitionRepo = $em->getRepository('DitttoRecognitionBundle:Recognition');
        $totalSentByUser = $recognitionRepo->getTotalRecognitionSentByUserId($user->getId());

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
            $em = $this->getDoctrine()->getManager();

            $recognition->setSender($user);
            foreach ($recognition->getReceivers() as $receiver) {
                $recognitionReceived = new RecognitionReceived();
                $recognitionReceived->setReceiver($receiver);
                $recognition->addrecognitionReceived($recognitionReceived);
            }

            $em->persist($recognition);
            $em->flush();
        }
        return $this->render('DitttoRecognitionBundle:Default:recogniseUser.html.twig',
            array('form' => $form->createView())
        );
    }
}
