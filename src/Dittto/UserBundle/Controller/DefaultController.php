<?php

namespace Dittto\UserBundle\Controller;

use Dittto\UserBundle\Entity\Repository\UserRepository;
use Dittto\UserBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render('DitttoUserBundle:Default:index.html.twig');
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function userSearchByNameAction(Request $request)
    {
        $searchData = $request->query->all();

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var UserRepository $userRepo */
        $userRepo = $em->getRepository('DitttoUserBundle:User');
        $users = $userRepo->findUserByFullName($searchData);

        $data = array();
        /** @var User $user */
        foreach ($users as $user) {
            $data[] = array(
                'id' => $user->getId(),
                'text' => $user->getFullname(),
                'name' => $user->getFullname()
            );
        }

        $jsonResponse = new JsonResponse();
        // Provide list of users who match
        $searchResult =  $jsonResponse->setData(array(
            'data' => $data
        ));
        return $searchResult;

    }
}
