<?php

namespace Dittto\UserBundle\Controller;

use Dittto\UserBundle\Form\CredentialType;
use Dittto\UserBundle\Form\ProfileType;
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
        $form = $this->createForm(ProfileType::class, $user);

        $form->handleRequest($request);

        // ensure form has error or not!
        $hasError = !empty((string)$form->getErrors(true, false));
        if ($form->isSubmitted() && $form->isValid()) {
            $userManager = $this->get('fos_user.user_manager');
            // encrypt password
            $user->setPlainPassword($user->getPassword());
            $userManager->updateUser($user, true);

            // add success message
            $this->addFlash(
                'success',
                'Your profile successfully updated.'
            );
        }

        return $this->render('DitttoUserBundle:Default:profile.html.twig',
            array(
                'form' => $form->createView(),
                'hasError' => $hasError
                )
        );
    }

    public function updatePasswordAction(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(CredentialType::class, $user);

        $form->handleRequest($request);

        // ensure form has error or not!
        $hasError = !empty((string)$form->getErrors(true, false));
        if ($form->isSubmitted() && $form->isValid()) {
            $userManager = $this->get('fos_user.user_manager');
            // encrypt password
            $user->setPlainPassword($user->getPassword());
            $userManager->updateUser($user, true);

            // add success message
            $this->addFlash(
                'success',
                'Your password successfully updated.'
            );
        }

        return $this->render('DitttoUserBundle:Default:updatePassword.html.twig',
            array(
                'form' => $form->createView(),
                'hasError' => $hasError
            )
        );

    }
}
