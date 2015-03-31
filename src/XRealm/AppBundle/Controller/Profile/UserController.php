<?php
namespace XRealm\AppBundle\Controller\Profile;

use XRealm\AppBundle\Controller\Controller as BaseController;
use Symfony\Component\HttpFoundation\Request;
use XRealm\AppBundle\Entity\GroupBossStatus;

class UserController extends BaseController
{
    public function selfAction(Request $request)
    {
        return $this->redirect($this->generateUrl('data_character_show', array('identifier' => $this->getUser()->getUsername())));
    }
}