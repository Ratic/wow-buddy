<?php

namespace XRealm\AppBundle\Controller;

use XRealm\AppBundle\Controller\Controller as BaseController;
use Symfony\Component\HttpFoundation\Request;

class IndexController extends BaseController
{
    public function indexAction(Request $request)
    {
        if($this->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            if($this->getUser()->getUsername() == 'Kiki' || $this->getUser()->getUsername() == 'Tobi' || $this->getUser()->getUsername() == 'numv')
            {
                $this->addFlash('rainbow', 'Daya ist (un)cool ;)');
            }
        }
        
        return $this->render('XRealmAppBundle:Pages:index.html.twig');
    }
}
