<?php
namespace XRealm\AppBundle\Controller\Api;

use XRealm\AppBundle\Controller\Controller as BaseController;
use XRealm\AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class ApiController extends BaseController
{
    protected function matchAuth(Request $request, User $user)
    {
         $token = $request->get('oauth_token', false);
         $now = new \DateTime();

         if($user->getApiAuth() != $token || $user->getAuthExpiresAt() < $now || empty($token))
         {
             throw new AccessDeniedHttpException("Auth Token Fail");
         }
    }
    protected function getAuthUser()
    {
        $token = $request->get('oauth_token', false);
        $user = $this->getRepository('XRealmAppBundle:User')->findOneByApiAuth($token);
         if(empty($user) || empty($token))
         {
             throw new AccessDeniedHttpException("Auth Token Fail");
         }
         return $user;
    }
    protected function authError()
    {
         throw new AccessDeniedHttpException("No Permissions");
    }

    protected function prepareCharacterData($character)
    {
        unset($character['progression']);
        $character['items'] = json_decode($character['items'], true);
        $character['averageItemLevel'] = $character['items']['averageItemLevel'];
        $character['averageItemLevelEquipped'] = $character['items']['averageItemLevelEquipped'];
        unset($character['items']);
        unset($character['verifyItems']);
        return $character;
    }
}
