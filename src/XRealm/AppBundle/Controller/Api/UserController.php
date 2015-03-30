<?php
namespace XRealm\AppBundle\Controller\Api;

use XRealm\AppBundle\Controller\Controller as BaseController;
use XRealm\AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class UserController extends BaseController
{
    public function getAction(Request $request, User $user)
    {
        $token = $request->get('oauth_token', false);
        
         $query = $this->get('doctrine')->getEntityManager()
        ->createQuery("SELECT u as user FROM XRealmAppBundle:User u "
                . "WHERE u.id = :id")
        ->setParameter('id', $user->getId());
         
         $data = $query->getArrayResult();
         
         $now = new \DateTime();
         if($data[0]['user']['apiAuth'] != $token || $user->getAuthExpiresAt() < $now)
         {
             throw new AccessDeniedHttpException("Auth Token Fail");
         }
         if(empty($data))
         {
             throw $this->createNotFoundException('The user does not exist');
         }


         $result = $data[0]['user'];
         unset($result['password']);
         unset($result['salt']);
         unset($result['apiAuth']);
    
        return new JsonResponse($result);
    }
    
    public function loginAction($username, $password)
    {
        $response = array(
            'status' => 404,
        );
        $user = $this->getRepository('XRealmAppBundle:User')->findOneByUsername($username);
        if(empty($user))
        {
            return new JsonResponse($response);
        }
        $encoder = $this->get('security.encoder_factory')->getEncoder($user);
        
        
        
        if($user->getPassword() != $encoder->encodePassword($password, $user->getSalt()))
        {
            $response['status'] = 403;
            return new JsonResponse($response);
        }
        
        
        $response['status'] = 200;
        if(empty($user->getApiAuthToken()))
        {
            $user->setApiAuthToken(md5(uniqid(null, true)));
            $this->persist($user, true);
        }
        $expires = new \DateTime();
        
        if($user->getAuthExpiresAt() < $expires)
        {
            $expires->modify('+1day');
            $user->setAuthExpiresAt($expires);
            $user->setApiAuthToken(md5(uniqid(null, true)));
            $this->persist($user, true);
        }

        
        
        
        $response['authToken'] = $user->getApiAuthToken();
        $response['user'] = $user->getId();
        $response['expires'] = $user->getAuthExpiresAt()->format('y-m-d H:i:s');
        return new JsonResponse($response);
        
    }
}
