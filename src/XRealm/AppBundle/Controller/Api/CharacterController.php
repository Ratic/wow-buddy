<?php
namespace XRealm\AppBundle\Controller\Api;

use XRealm\AppBundle\Controller\Api\ApiController as BaseController;
use XRealm\AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use XRealm\AppBundle\Entity\BlizzCharacter;
use Doctrine\ORM\Query\Expr;

class CharacterController extends BaseController
{
    public function getAllAction(Request $request, User $user)
    {
        $this->matchAuth($request, $user);

        $characters = $this->getDoctrine()
            ->getRepository('XRealmAppBundle:BlizzCharacter')
            ->createQueryBuilder('c')
            ->select('c, r')
            ->join('c.realm', 'r')
            ->where('c.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        $return = array();
        foreach($characters as $character)
        {
            $slug = strtolower($character['name']) . '-' . $character['realm']['slug'];
            
            
           
            if($character['isVerified'])
            {
                $character = $this->prepareCharacterData($character);
                $return[] = $character;
            }
            else
            {
                 unset($character['progression']);
                $return[] = array(
                    'status' => 403,
                    'error' => 'not_verified',
                    'name' => $character['name'],
                    'realm' => $character['realm'],
                    'verify_items' => $character['verifyItems'],
                );
            }

        }
        return new JsonResponse(array('characters' => $return));
    }
    public function getGroupsAction(Request $request, BlizzCharacter $character)
    {
        $user = $character->getUser();
        $this->matchAuth($request, $user);
        $groups = $this->getDoctrine()
            ->getRepository('XRealmAppBundle:Group')
            ->createQueryBuilder('g')
            ->select('g, gics, c, r, e')
            ->leftJoin('g.involvedCharacters', 'gic')
            ->leftJoin('g.involvedCharacters', 'gics')
            ->join('gics.character', 'c')
            ->join('c.realm', 'r')
            ->leftJoin('g.events', 'e', Expr\Join::WITH, 'e.startAt > :now')
            ->where('gic.character = :character')
            ->setParameters(array(
                'character' => $character,
                'now'   => new \DateTime(),
            ))
            ->getQuery()
            ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        foreach($groups as &$group)
        {
            unset($group['description']);
            foreach($group['involvedCharacters'] as &$char)
            {
                $char['character'] = $this->prepareCharacterData($char['character']);
            }

        }
        return new JsonResponse(array('groups' => $groups));
    }

}
