<?php

namespace XRealm\AppBundle\Controller;

use XRealm\AppBundle\Controller\Controller as BaseController;

class IndexController extends BaseController
{
    public function indexAction()
    {
        return $this->render('XRealmAppBundle:Pages:index.html.twig');
    }
}
