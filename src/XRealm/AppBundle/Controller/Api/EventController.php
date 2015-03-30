<?php
namespace XRealm\AppBundle\Controller\Api;

use XRealm\AppBundle\Controller\Api\ApiController as BaseController;
use XRealm\AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use XRealm\AppBundle\Entity\BlizzCharacter;
use Doctrine\ORM\Query\Expr;
use XRealm\AppBundle\Entity\Event;

class EventController extends BaseController
{
    public function getEventAction(Request $request, BlizzCharacter $character, Event $event)
    {
        $user = $character->getUser();
        $this->matchAuth($request, $user);
        $member = $this->getRepository('XRealmAppBundle:EventInvolvedCharacter')->findOneBy(array(
            'character' => $character,
            'event' => $event,
        ));
        if(empty($member))
        {
            $this->authError();
        }
        $return = $this->getDoctrine()
            ->getRepository('XRealmAppBundle:Event')
            ->createQueryBuilder('e')
            ->select('e, eic, c, r')
            ->leftJoin('e.involvedCharacters', 'eic')
            ->join('eic.character', 'c')
            ->join('c.realm', 'r')
            ->where('e = :event')
            ->setParameters(array(
                'event' => $event,
            ))
            ->getQuery()
            ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        $return = $return[0];
        
        foreach($return['involvedCharacters'] as &$char)
        {
            $char['character'] = $this->prepareCharacterData($char['character']);
        }
        return new JsonResponse($return);
    }
}