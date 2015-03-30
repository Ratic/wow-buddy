<?php

namespace XRealm\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('XRealmUserBundle:Default:index.html.twig', array('name' => $name));
    }
}
