<?php

namespace Dittto\RecognitionBundle\Controller;

use Dittto\RecognitionBundle\Entity\Criteria;
use Dittto\RecognitionBundle\Entity\Recognition;
use Dittto\RecognitionBundle\Entity\RecognitionReceived;
use Dittto\RecognitionBundle\Entity\Repository\RecognitionReceivedRepository;
use Dittto\RecognitionBundle\Entity\Repository\RecognitionRepository;
use Dittto\RecognitionBundle\Form\RecognitionType;
use Dittto\UserBundle\Entity\Repository\UserRepository;
use Dittto\UserBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
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

        // list of recognition received but not replied yet
        $notRepliedRecognitions = $recognitionReceivedRepo->getResponseRequiredRecognitionsByUserId($user->getId());

        // everything about the replay back, message, sender, criteria
        $notRepliedRecognitionDetails = $this->generateReplayToMessage($notRepliedRecognitions);

        /** @var RecognitionRepository $recognitionRepo */
        $recognitionRepo = $em->getRepository('DitttoRecognitionBundle:Recognition');
        $totalSentByUser = $recognitionRepo->getTotalRecognitionSentByUserId($user->getId());

        // displayed in charts
        $userVsTotal = array(
            'totalSentByUser' => $totalSentByUser,
            'totalReceivedByUser' => $totalReceivedByUser,
            'totalRecognitions' => $totalRecognitions,
            );

        return $this->render('DitttoRecognitionBundle:Default:dashboard.html.twig',
            array(
                'userVsTotal' => json_encode($userVsTotal),
                'notRepliedRecognitionDetails' => $notRepliedRecognitionDetails
                )
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

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function recogniseBackAction(Request $request)
    {
        $postData = $request->request->all();

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var UserRepository $userRepo */
        $userRepo = $em->getRepository('DitttoUserBundle:User');
        $criteriaRepo = $em->getRepository('DitttoRecognitionBundle:Criteria');
        /** @var RecognitionRepository $recognitionRepo */
        $recognitionRepo = $em->getRepository('DitttoRecognitionBundle:Recognition');

        // this is who we're replying to :-)
        $repliedTo = $userRepo->find($postData['senderId']);
        // criteria used for ditto :-)
        $criteria = $criteriaRepo->find($postData['criteriaId']);
        /** @var Recognition $originalRecognition */
        $originalRecognition = $recognitionRepo->find($postData['recognitionId']);

        // new recognition
        $responseRecognition = new Recognition();
        $responseRecognition->setSender($this->getUser());
        $responseRecognition->getCriteria()->add($criteria);

        // add recognition response to recognition
        $recognitionReceived = new RecognitionReceived();
        $recognitionReceived->setReceiver($repliedTo);
        $recognitionReceived->setResponseType(RecognitionReceived::NOT_REQUIERED);
        $responseRecognition->addrecognitionReceived($recognitionReceived);

        $em->persist($responseRecognition);

        // update original recognition
        $recognitionReceiveds = $originalRecognition->getRecognitionReceiveds();
        /** @var RecognitionReceived $recognitionReceived */
        foreach ($recognitionReceiveds as $recognitionReceived) {
            $recognitionReceived->setResponseType(RecognitionReceived::REPLIED);
            $em->persist($recognitionReceived);
        }
        $em->flush();

        $data = ['success' => true];

        return new JsonResponse($data);
    }

    /**
     * TODO: This should be done as a twig extension
     *
     * @param $notRepliedRecognitions
     * @return array
     */
    private function generateReplayToMessage($notRepliedRecognitions)
    {
        $notRepliedRecognitionDetails = array();
        /** @var RecognitionReceived $notRepliedRecognition */
        foreach ($notRepliedRecognitions as $notRepliedRecognition) {
            /** @var Recognition $recognition */
            $recognition = $notRepliedRecognition->getRecognition();
            $sender = $recognition->getSender();
            $listCriteria = $recognition->getCriteria();
            /** @var Criteria $criteria */
            foreach ($listCriteria as $criteria) {
                $notRepliedRecognitionMessage =
                    '<b>' . $sender->getFullname() . '</b>'
                    . ' sent you " <b>' . $criteria->getTitle() . '</b>'
                    . '" at '
                    . $notRepliedRecognition->getReceivedAt()
                ;

                $notRepliedRecognitionDetails[] = array(
                    'criteriaId' => 3, // TODO: at the moment only like. This should be fetch from DB
                    'senderId' => $sender->getId(),
                    'message' => $notRepliedRecognitionMessage,
                    'hasReplied' => false,
                    'recognitionId' => $recognition->getId()
                );
            }
        }

        return $notRepliedRecognitionDetails;
    }
}
