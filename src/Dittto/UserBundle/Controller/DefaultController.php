<?php

namespace Dittto\UserBundle\Controller;

use Dittto\UserBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function profileAction(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        // ensure form has error or not!
        $hasError = !empty((string)$form->getErrors(true, false));
        if ($form->isSubmitted() && $form->isValid()) {
            $userManager = $this->get('fos_user.user_manager');
            // encrypt password
            $user->setPlainPassword($user->getPassword());
            $userManager->updateUser($user, true);
        }

        return $this->render('DitttoUserBundle:Default:profile.html.twig',
            array(
                'form' => $form->createView(),
                'hasError' => $hasError
                )
        );
    }
}
