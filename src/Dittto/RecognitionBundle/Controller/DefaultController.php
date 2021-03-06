<?php

namespace Dittto\RecognitionBundle\Controller;

use AppBundle\Service\DateTime\Date;
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
//        TODO: This should be a service, chartGenerator, get an entity and return diff. reports about that entity!!
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

        // list of new recognition received that not responded yet
        $newRecognitions = $recognitionReceivedRepo->getNewRecognitionsByUserId($user->getId());

        // everything about the replay back, message, sender, criteria
        $newRecognitionDetails = $this->generateReplayToMessage($newRecognitions);

        /** @var RecognitionRepository $recognitionRepo */
        $recognitionRepo = $em->getRepository('DitttoRecognitionBundle:Recognition');
        $totalSentByUser = $recognitionRepo->getTotalRecognitionSentByUserId($user->getId());
        $rankCurrentDetails = $recognitionRepo->getUserSentRankNow($user->getId());
        $rankChanged = $recognitionRepo->getRankChangedSinceLastMonth($user->getId());

        // displayed in charts
        $userVsTotal = array(
            'totalSentByUser' => $totalSentByUser,
            'totalReceivedByUser' => $totalReceivedByUser,
            'totalRecognitions' => $totalRecognitions,
            );

        // TODO: this should come from client table and ideally configurable
        $totalRecognitionsGoal = 10;

        $sentPercentage = ($totalSentByUser * 100) / $totalRecognitionsGoal;

        return $this->render('DitttoRecognitionBundle:Default:dashboard.html.twig',
            array(
                'userVsTotal' => json_encode($userVsTotal),
                'notRepliedRecognitionDetails' => $newRecognitionDetails,
                'totalSentByUser' => $totalSentByUser,
                'totalReceivedByUser' => $totalReceivedByUser,
                'totalRecognitionsGoal' => $totalRecognitionsGoal,
                'rankCurrentDetails' => $rankCurrentDetails,
                'rankChanged' => $rankChanged,
                'sentPercentage' => $sentPercentage
                )
        );
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function recognitionAction(Request $request)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        /** @var RecognitionReceivedRepository $recognitionReceivedRepo */
        $recognitionReceivedRepo = $em->getRepository('DitttoRecognitionBundle:RecognitionReceived');
        $receivedRecognitionRaw = $recognitionReceivedRepo->getRecognitionReceivedListByUserId($user->getId());
        $receivedRecognition = $this->generateRecognitionsReceived($receivedRecognitionRaw);
        $totalReceivedByUser = $recognitionReceivedRepo->getRecognitionReceivedByUserId($user->getId());
        $itemsPerPage = 5;

        return $this->render('DitttoRecognitionBundle:Default:recognition.html.twig',
            array(                
                'receivedRecognition' => json_encode($receivedRecognition),
                'receivedRecognitionCount' => $totalReceivedByUser,
                'itemsPerPage' => $itemsPerPage,
                'currentPage' => 1
            )
        );
    }

    /**
     * @param $page
     * @return JsonResponse
     */
    public function recognitionReceivedAction($page)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $itemsPerPage = 5;
        $offset = ($page - 1) * $itemsPerPage;

        /** @var RecognitionReceivedRepository $recognitionReceivedRepo */
        $recognitionReceivedRepo = $em->getRepository('DitttoRecognitionBundle:RecognitionReceived');
        $totalReceivedByUser = $recognitionReceivedRepo->getRecognitionReceivedByUserId($user->getId());
        $receivedRecognitionRaw = $recognitionReceivedRepo->getRecognitionReceivedListByUserId($user->getId(), $offset, $itemsPerPage);
        $receivedRecognition = $this->generateRecognitionsReceived($receivedRecognitionRaw);

        $data = [
            'currentPage' => $page,
            'itemsPerPage' => $itemsPerPage,
            'receivedRecognitionCount' => $totalReceivedByUser,
            'receivedRecognition' => $receivedRecognition,            
        ];

        return new JsonResponse($data);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function recogniseAction(Request $request)
    {
        $user = $this->getUser();

        $recognition = new Recognition();
        $form = $this->createForm(
            RecognitionType::class,
            $recognition,
            array('user' => $this->getUser())
        );

        // update the recognition object with the submitted form
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            // keep track of receivers to be displayed in  success message
            $receiversName = array();
            $recognition->setSender($user);
            foreach ($recognition->getReceivers() as $receiver) {
                $recognitionReceived = new RecognitionReceived();
                $recognitionReceived->setReceiver($receiver);
                $recognition->addRecognitionReceived($recognitionReceived);

                $receiversName[] = $receiver->getFullName();
            }

            // add success message
            $this->addFlash(
                'success',
                'Great! You just Dittto ' . implode(',', $receiversName) . ''
            );

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
        $recognitionReceived->setResponseType(RecognitionReceived::RESPONSE);
        $responseRecognition->addRecognitionReceived($recognitionReceived);

        $em->persist($responseRecognition);

        // update original recognition
        $recognitionReceiveds = $originalRecognition->getRecognitionReceiveds();
        /** @var RecognitionReceived $recognitionReceived */
        foreach ($recognitionReceiveds as $recognitionReceived) {
            $recognitionReceived->setResponseType(RecognitionReceived::RESPONDED);
            $em->persist($recognitionReceived);
        }
        $em->flush();

        $data = ['success' => true];

        return new JsonResponse($data);
    }

    /**
     * TODO: This should be done as a twig extension
     *
     * @param RecognitionReceived[] $newRecognitionReceivedList
     * @return array
     */
    private function generateReplayToMessage($newRecognitionReceivedList)
    {
        /** @var Date $dateService */
        $dateService = $this->get('dateTime_service');

        $newRecognitionDetails = array();
        /** @var RecognitionReceived $newRecognitionReceived */
        foreach ($newRecognitionReceivedList as $newRecognitionReceived) {
            /** @var Recognition $recognition */
            $recognition = $newRecognitionReceived->getRecognition();
            $sender = $recognition->getSender();
            $listCriteria = $recognition->getCriteria();
            /** @var Criteria $criteria */
            foreach ($listCriteria as $criteria) {
                $recognitionMessage =
                    '<span class="strong">' . $sender->getFullname() . '</span>'
                    . ' sent you <span class="strong">' . $criteria->getTitle() . '</span>'
                    . ' at '
                    . $dateService->getHumanTiming($newRecognitionReceived->getReceivedAt())
                ;

                if ($newRecognitionReceived->getResponseType() == RecognitionReceived::RESPONDED) {
                    // if it's responded
                    $recognitionMessage .= '<br>' . ' You responded at ' . $newRecognitionReceived->getRepliedAt();
                }

                if ($newRecognitionReceived->getResponseType() == RecognitionReceived::RESPONSE) {
                    $responseRecognition = $newRecognitionReceived->getRecognition();
                    $senderName = $responseRecognition->getSender()->getFullname();
                    $criteriaTitle = $responseRecognition->getSingleCriteria()->getTitleToDisplay();
                    $responseType = $dateService->getHumanTiming($responseRecognition->getSentAt());
                    $originalCriteriaTitle = $recognition->getSingleCriteria()->getTitle();

                    $recognitionMessage =
                        '<span class="strong">'. $senderName . '</span>' . ' ' . $criteriaTitle . ' your <span class="strong">' . $originalCriteriaTitle . '</span> recognition ' . $responseType . ' ago'
                        ;
                }

                $newRecognitionDetails[] = array(
                    'criteriaId' => 3, // TODO: at the moment this is the only response (like). This should be fetched from DB
                    'senderId' => $sender->getId(),
                    'message' => $recognitionMessage,
                    'hasReplied' => false,
                    'recognitionId' => $recognition->getId(),
                    'responseType' => $newRecognitionReceived->getResponseType()
                );
            }
        }

        return $newRecognitionDetails;
    }

    /**
     * This generate a list of recognition received by user, which will be displayed in a table view
     *
     * @param $recognitions
     * @return array
     */
    private function generateRecognitionsReceived($recognitions)
    {   
        $recognitionReceivedDetails = array();

        /** @var RecognitionReceived $recognitionReceived */
        foreach ($recognitions as $recognitionReceived) {
            $recognition = $recognitionReceived->getRecognition();
            $sender = $recognition->getSender();
            $senderName = $recognition->getSender()->getFullname();

            $recognitionReceivedDetails[] = array(
                'id' => $recognitionReceived->getId(),
                'senderId' => $sender->getId(),
                'senderName' => $senderName,
                'recognitionId' => $recognition->getId(),
                'responseType' => $recognition->getAllCriteriaTitles(),
                'receivedAt' => $recognitionReceived->getReceivedAt()
            );

        }

        return $recognitionReceivedDetails;

    }


}
