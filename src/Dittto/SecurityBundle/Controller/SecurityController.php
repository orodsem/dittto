<?php

namespace Dittto\SecurityBundle\Controller;

use AppBundle\Service\Security\Encryption;
use Dittto\SecurityBundle\Entity\Repository\ResetPasswordRepository;
use Dittto\SecurityBundle\Entity\ResetPassword;
use Dittto\SecurityBundle\Form\ResetPasswordType;
use Dittto\UserBundle\Entity\Repository\UserRepository;
use Dittto\UserBundle\Entity\Role;
use Dittto\UserBundle\Entity\User;
use Dittto\UserBundle\Form\CredentialType;
use Doctrine\ORM\EntityManager;
use FOS\UserBundle\Controller\SecurityController as BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;


class SecurityController extends BaseController
{
    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction()
    {
        return $this->redirectToRoute('dittto_recognition_dashboard');
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function loginAction(Request $request)
    {
        /** @var $session \Symfony\Component\HttpFoundation\Session\Session */
        $session = $request->getSession();

        $authErrorKey = Security::AUTHENTICATION_ERROR;
        $lastUsernameKey = Security::LAST_USERNAME;

        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has($authErrorKey)) {
            $error = $request->attributes->get($authErrorKey);
        } elseif (null !== $session && $session->has($authErrorKey)) {
            $error = $session->get($authErrorKey);
            $session->remove($authErrorKey);
        } else {
            $error = null;
        }

        if (!$error instanceof AuthenticationException) {
            $error = null; // The value does not come from the security component.
        }

        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get($lastUsernameKey);

        $csrfToken = $this->has('security.csrf.token_manager')
            ? $this->get('security.csrf.token_manager')->getToken('authenticate')->getValue()
            : null;

        return $this->renderLogin(array(
            'is_nav_hidden'  => true,
            'last_username' => $lastUsername,
            'error' => $error,
            'csrf_token' => $csrfToken,
        ));
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function recoveryLinkAction(Request $request)
    {
        $clientShortName = $this->container->get( 'kernel' )->getEnvironment();

        $resetPassword = new ResetPassword();
        $form = $this->createForm(
            ResetPasswordType::class,
            $resetPassword
        );

        // update the recognition object with the submitted form
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var EntityManager $em */
            $em = $this->getDoctrine()->getManager();
            /** @var UserRepository $userRepo */
            $userRepo = $em->getRepository('DitttoUserBundle:User');
            $user = $userRepo->findBy(array('email' => $resetPassword->getIdentifier()));

            $message = "We've sent you a verification email. We sent a recovery link to your email address: " .
                $resetPassword->getIdentifier();

            if (count($user) != 1) {
                return $this->renderMessagePage($message);
            }

            $user = $user[0];
            if (!$user instanceof User) {
                return $this->renderMessagePage($message);
            }

            /** @var ResetPasswordRepository $resetPasswordRepo */
            $resetPasswordRepo = $em->getRepository(ResetPassword::class);
            $resetPasswordRepo->expiresTokenByIdentifier($resetPassword->getIdentifier());

            // generate the token before save it
            $resetPassword->generateToken();
            $resetPassword->setEncryptIdentifier($this->get('security_service'));

            $em->persist($resetPassword);
            $em->flush($resetPassword);

            $emailMessage = (new \Swift_Message('Recovery Link'))
                ->setFrom('noreply@dittto.com.au', 'DitttO')
                ->setTo($resetPassword->getIdentifier())
                ->setBody(
                    $this->renderView(
                        'DitttoSecurityBundle:Security:' . $clientShortName . '/recoveryLinkTemplate.html.twig',
                        array(
                            'token' => $resetPassword->getToken(),
                            'encyIdentifier' => $resetPassword->getEncryptedIdentifier(),
                            'user' => $user
                        )
                    ),
                    'text/html'
                );

            $this->get('mailer')->send($emailMessage);
            return $this->renderMessagePage($message);
        }

        $data = array(
            'is_nav_hidden' => true,
            'form' => $form->createView()
        );
        return $this->render('DitttoSecurityBundle:Security:forgotPassword.html.twig', $data);
    }


    /**
     * Reset password if recovery link is valid, which means
     * the token is valid (not used yet)
     * the reset password identifier (e.g. email/username) found in the system and token assigned to that identifier
     *
     * @param Request $request
     * @param $token
     * @param $encyIdentifier
     * @return Response
     */
    public function resetPasswordAction(Request $request, $token, $encyIdentifier)
    {
        /** @var Encryption $securityService */
        $securityService = $this->get('security_service');
        $recoverIdentifier = $securityService->decrypt($encyIdentifier);

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        /** @var ResetPasswordRepository $resetPasswordRepo */
        $resetPasswordRepo = $em->getRepository(ResetPassword::class);
        $resetPassword = $resetPasswordRepo->getByToken($token);

        if (!$resetPassword instanceof ResetPassword) {
            // token is invalid, then inform user
            $data = array(
                'is_nav_hidden' => true,
                'message' => "Invalid Token"
            );
            return $this->render('DitttoSecurityBundle:Security:resetPasswordMessage.html.twig', $data);
        }

        /** @var UserRepository $userRepo */
        $userRepo = $em->getRepository(User::class);
        $user = $userRepo->findBy(array('email' => $recoverIdentifier, 'isDeleted' => 0));

        if (count($user) != 1) {
            // email found
            $data = array(
                'is_nav_hidden' => true,
                'message' => "There is something is not right with this recovery link, please try again!"
            );
            return $this->render('DitttoSecurityBundle:Security:resetPasswordMessage.html.twig', $data);
        }

        /** @var User $user */
        $user = $user[0];
        $form = $this->createForm(CredentialType::class, $user);
        $form->handleRequest($request);

        // ensure form has error or not!
        $hasError = !empty((string)$form->getErrors(true, false));
        if ($form->isSubmitted() && $form->isValid()) {
            $userManager = $this->get('fos_user.user_manager');
            // encrypt password
            $user->setPlainPassword($user->getPassword());
            $userManager->updateUser($user, true);

            // expires token
            $resetPassword = $resetPassword->expiresToken();
            $em->persist($resetPassword);
            $em->flush();

            // add success message
            $this->addFlash(
                'success',
                'Your password successfully reset. Please try to login now.'
            );

            return $this->redirectToRoute('dittto_security_login');
        }

        $data = array(
            'is_nav_hidden' => true,
            'hasError' => $hasError,
            'form' => $form->createView()
        );
        return $this->render('DitttoSecurityBundle:Security:resetPassword.html.twig', $data);
    }

    /**
     * Renders the login template with the given parameters. Overwrite this function in
     * an extended controller to provide additional data for the login template.
     *
     * @param array $data
     *
     * @return Response
     */
    protected function renderLogin(array $data)
    {
        return $this->render('DitttoSecurityBundle:Security:login.html.twig', $data);
    }

    /**
     * @param $message
     * @return Response
     */
    protected function renderMessagePage($message)
    {
        $data = array(
            'is_nav_hidden' => true,
            'message' => $message
        );
        return $this->render('DitttoSecurityBundle:Security:forgotPasswordMessage.html.twig', $data);
    }
}
