<?php
namespace XRealm\AppBundle\Controller\Profile;

use XRealm\AppBundle\Controller\Controller as BaseController;
use Symfony\Component\HttpFoundation\Request;
use XRealm\AppBundle\Entity\GroupBossStatus;

class UserController extends BaseController
{
    public function selfAction(Request $request)
    {
        return $this->redirect($this->generateUrl('profile_user_show', array('identifier' => $this->getUser()->getUsername())));
    }
    public function showAction(Request $request, $identifier)
    {
        $data = explode('-', $identifier, 2);
        if(!is_array($data) || count($data) < 2)
        {
            return $this->redirect($this->generateUrl('index'));
        }
        $realm = $this->getRepository('XRealmAppBundle:BlizzRealm')->findOneBySlug($data[1]);
        if(empty($realm))
        {
            return $this->redirect($this->generateUrl('index'));
        }
        $character = $this->getRepository('XRealmAppBundle:BlizzCharacter')->findOneBy(array(
            'name'  => $data[0],
            'realm' => $realm,
        ));
        if(empty($character))
        {
            return $this->redirect($this->generateUrl('index'));
        }
        $userCharacters = $this->getRepository('XRealmAppBundle:BlizzCharacter')->findBy(array(
            'user'  => $character->getUser(),
            'isVerified'    => true,
        ), array('items' => 'DESC'));
        $self = false;
        if($this->get('blizz_character')->getSelected())
        {
            if($character->getId() == $this->get('blizz_character')->getSelected()->getData()->getId())
            {
                $self = true;
            }
        }

        if($self)
        {
            $groups = $character->getGroups();
        }
        else if($this->get('blizz_character')->getSelected())
        {
            $groups = $this->getRepository('XRealmAppBundle:GroupInvolvedCharacter')->findForPermission($character, $this->get('blizz_character')->getSelected()->getData());
        }
        else
        {
            $groups = $this->getRepository('XRealmAppBundle:GroupInvolvedCharacter')->findForPermission($character, false);
        }

        $raids = $this->getRepository('XRealmAppBundle:Raid')->findBy(array(
            'isActive' => true,
        ));

        $raidprogress = array();
        $charprogress = $character->getProgression();
        foreach($raids as $raid)
        {
            $raidprogress[$raid->getBlizzId()] = array(
                'entity'    => $raid,
            );
            $bosses = array();
            foreach($raid->getBosses() as $boss)
            {
                $bosses[$boss->getBlizzId()] = array(
                    'entity'    => $boss,
                    'highest'   => GroupBossStatus::STATUS_NONE,
                    GroupBossStatus::STATUS_NORMAL => 0,
                    GroupBossStatus::STATUS_HEROIC => 0,
                    GroupBossStatus::STATUS_MYTHIC => 0,
                );
                if(empty($charprogress[$raid->getBlizzId()]))
                {
                    continue;
                }
            }
            
            foreach($charprogress[$raid->getBlizzId()]['bosses'] as $charbossprog)
            {
                $highest = GroupBossStatus::STATUS_NONE;
                if((int) $charbossprog['mythicKills'] > 0)
                {
                    $highest = GroupBossStatus::STATUS_MYTHIC;
                }
                else if((int) $charbossprog['heroicKills'] > 0)
                {
                    $highest = GroupBossStatus::STATUS_HEROIC;
                }
                else if((int) $charbossprog['normalKills'] > 0)
                {
                    $highest = GroupBossStatus::STATUS_NORMAL;
                }
                $bosses[$charbossprog['id']][GroupBossStatus::STATUS_NORMAL] = $charbossprog['normalKills'];
                $bosses[$charbossprog['id']][GroupBossStatus::STATUS_HEROIC] = $charbossprog['heroicKills'];
                $bosses[$charbossprog['id']][GroupBossStatus::STATUS_MYTHIC] = $charbossprog['mythicKills'];
                $bosses[$charbossprog['id']]['highest'] = $highest;
            }
            $raidprogress[$raid->getBlizzId()]['bosses'] = $bosses;
        }

        $items = $character->getItems();
        return $this->render('XRealmAppBundle:Pages/Profile:user.html.twig',array(
            'character'     => $character,
            'self'          => $self,
            'groups'        => $groups,
            'raids'         => $raids,
            'progress'      => $raidprogress,
            'userCharacters'=> $userCharacters,
            'mainstats'     => array(
                'str', 'agi', 'int', 'sta',
            ),
            'substats'         => array(
                'crit', 'haste', 'mastery', 'multistrike'
            ),
        ));
    }
}