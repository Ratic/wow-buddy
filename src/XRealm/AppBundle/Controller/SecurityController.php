<?php

// src/xRealm/AppBundle/Controller/SecurityController.php

namespace XRealm\AppBundle\Controller;

use XRealm\AppBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContextInterface;
use XRealm\AppBundle\Form\Type\UserType;
use XRealm\AppBundle\Entity\User;

class SecurityController extends Controller {

    public function loginAction(Request $request) {
        if ($this->isGranted('ROLE_USER')) {
            return $this->redirect($this->generateUrl('index'));
        }

        $session = $request->getSession();
        $redirect = $request->get('_target_path');
        $router = $this->container->get('router');
        if (!$redirect || !$this->generateUrl($redirect)) {
            $redirect = false;
        }


        // get the login error if there is one
        if ($request->attributes->has(SecurityContextInterface::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                    SecurityContextInterface::AUTHENTICATION_ERROR
            );
        } elseif (null !== $session && $session->has(SecurityContextInterface::AUTHENTICATION_ERROR)) {
            $error = $session->get(SecurityContextInterface::AUTHENTICATION_ERROR);
            $session->remove(SecurityContextInterface::AUTHENTICATION_ERROR);
        } else {
            $error = null;
        }

        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get(SecurityContextInterface::LAST_USERNAME);

        $user = new User();
        $form = $this->createForm(new UserType(), $user, array(
            'action' => $this->generateUrl('register'),
        ));

        return $this->render('XRealmAppBundle:Pages:login.html.twig', array(
            // last username entered by the user
            'last_username' => $lastUsername,
            'error' => $error,
            'form' => $form->createView(),
            'createdMessage' => false,
            'redirect' => $redirect,
        ));
    }

    public function registerAction(Request $request) {
        if ($this->isGranted('ROLE_USER')) {
            return $this->redirect($this->generateUrl('index'));
        }

        $error = null;
        $createdMessage = false;
        $user = new User();
        $form = $this->createForm(new UserType(), $user, array(
            'action' => $this->generateUrl('register'),
        ));

        $form->handleRequest($request);
        if ($form->isValid() && $form->isSubmitted()) {
            $encoder = $this->get('security.encoder_factory')->getEncoder($user);
            $password = $form->get('plainPassword')->getData();

            $password = $encoder->encodePassword($password, $user->getSalt());
            $user->setPassword($password);

            $role = $this->getRepository('XRealm\\AppBundle\\Entity\\Role')->findOneByRole('ROLE_USER');
            $user->addRole($role);

            $this->persist($user, true);
            $createdMessage = true;
        }

        return $this->render('XRealmAppBundle:Pages:login.html.twig', array(
            'form' => $form->createView(),
            'error' => $error,
            'createdMessage' => $createdMessage,
            'redirect' => false,
        ));
    }

}
