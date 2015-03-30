<?php
namespace XRealm\AppBundle\Controller;

use XRealm\AppBundle\Controller\Controller as BaseController;
use Symfony\Component\HttpFoundation\Request;
use XRealm\AppBundle\Entity\Group;
use XRealm\AppBundle\Form\Type\GroupType;
use XRealm\AppBundle\Entity\GroupInvolvedCharacter;
use XRealm\AppBundle\Entity\BlizzCharacter;
use XRealm\AppBundle\Form\Type\AddCharacterType;
use Symfony\Component\Form\FormError;
use XRealm\AppBundle\Entity\GroupBossStatus;
use XRealm\AppBundle\Entity\ActivityNotification;
use XRealm\AppBundle\Form\Type\ActivityPostType;
use XRealm\AppBundle\Entity\ActivityPost;
use XRealm\AppBundle\Entity\Activity;
use XRealm\AppBundle\Form\Type\EventType;
use XRealm\AppBundle\Entity\Event;
use XRealm\AppBundle\Entity\EventInvolvedCharacter;
use XRealm\AppBundle\Form\Type\EventInvolvedType;

class GroupsController extends BaseController
{
    protected $maxLeadingGroups = 3;
    
    public function indexAction(Request $request)
    {
        $top10 = $this->getRepository('XRealmAppBundle:Group')->findBy(array(
            'isPublic'  => true,
        ), array(
            'groupRating' => 'DESC',
        ), 10);

        if(!$this->get('blizz_character')->getSelected())
        {
            return $this->render('XRealmAppBundle:Pages:groups.html.twig', array(
                'groups' => array(),
                'selectedCharacter' => false,
                'invites'   => array(),
                'top10' => $top10,
            ));
        }
        $self = $this->get('blizz_character')->getSelected()->getData();
        $groups = $this->getRepository('XRealmAppBundle:Group')->findGroupsForCharacter($self);
        $selectedCharacter = $this->get('blizz_character')->getSelected()->getData();

        $invites =  $this->getRepository('XRealmAppBundle:Group')->findGroupsForCharacter($self, GroupInvolvedCharacter::STATUS_INVITED);

        return $this->render('XRealmAppBundle:Pages:groups.html.twig', array(
            'groups' => $groups,
            'selectedCharacter' => $selectedCharacter,
            'invites'   => $invites,
            'top10' => $top10,
        ));
    }
    
    public function inviteReplyAction($action, $slug)
    {
        $group = $this->getGroupByIdentifier($slug);
        if(empty($group))
        {
            $this->addFlash('danger', $this->get('translator')->trans('error.groupnotexists'));
            return $this->redirect($this->generateUrl('groups_index'));
        }
        $self = $this->getRepository('XRealmAppBundle:GroupInvolvedCharacter')->findOneBy(array(
            'group' => $group,
            'character' => $this->get('blizz_character')->getSelected()->getData(),
        ));
        if(!$self)
        {
            $this->addFlash('error', $this->get('translator')->trans('error.nogroupinvite'));
            return $this->redirect($this->generateUrl('groups_index'));
        }
        
        if($self->getStatus() != GroupInvolvedCharacter::STATUS_INVITED)
        {
            $this->addFlash('error', $this->get('translator')->trans('error.nogroupinvite'));
            return $this->redirect($this->generateUrl('groups_index'));
        }
        
        if($action == 'accept')
        {
            $self->setStatus(GroupInvolvedCharacter::STATUS_JOINED);
            $this->addFlash('success', $this->get('translator')->trans('message.joinedgroup', array(
                '%name%'    => $group->getName(),
            )));
            $this->persist($self, true);
            $this->addNotification($this->get('blizz_character')->getSelected()->getData(), $group, 'notification.joined_group');
            return $this->returnGroupPage($group);
        }
        else if($action == 'decline')
        {
            $this->remove($self, true);
            $this->addFlash('info', $this->get('translator')->trans('message.declinedgroup', array(
                '%name%'    => $group->getName(),
            )));
            $this->addNotification($this->get('blizz_character')->getSelected()->getData(), $group, 'notification.declined_group_invite');
            return $this->redirect($this->generateUrl('groups_index'));
        }
    }
    
    public function newAction(Request $request)
    {
        
        $leadingGroups = $this->getRepository('XRealmAppBundle:GroupInvolvedCharacter')->findBy(array(
            'character' => $this->get('blizz_character')->getSelected()->getData(),
            'rank'      => GroupInvolvedCharacter::RANK_LEADER,
        ));
        if(empty($leadingGroups))
        {
            $leadingGroups = 0;
        }
        else
        {
            $leadingGroups = count($leadingGroups);
        }
        
        $maxGroups = $this->maxLeadingGroups;
        if($maxGroups < $leadingGroups)
        {
            $maxGroups = $leadingGroups;
        }

        $group = new Group();
        $form = $this->createForm(new GroupType(), $group, array(
            'action' => $this->generateUrl('groups_create'),
            'type'   => 'new',
        ));

        $form->handleRequest($request);

        if($form->isValid() && $form->isSubmitted() && ($leadingGroups < $maxGroups))
        {
            $group->setSlug($this->generateSlug($group->getName()));
            $this->persist($group, true);
            $member = new GroupInvolvedCharacter();
            $member->setCharacter($this->get('blizz_character')->getSelected()->getData());
            $member->setGroup($group);
            $member->setRank(GroupInvolvedCharacter::RANK_LEADER);
            $member->setStatus(GroupInvolvedCharacter::STATUS_JOINED);
            $this->persist($member, true);

            $this->addFlash('success', $this->get('translator')->trans('message.group.created', array(
                '%name%' => $group->getName(),
            )));
            return $this->returnGroupPage($group);
        }
        
        return $this->render('XRealmAppBundle:Pages:creategroup.html.twig', array(
            'form' => $form->createView(),
            'leadingGroups' => $leadingGroups,
            'maxGroups' => $maxGroups,
        ));
        
    }

    protected function generateSlug($name)
    {
        $name = strtolower($name);
        $name = ereg_replace("[^A-Za-z0-9 ]", "", $name);
        $name = str_replace(' ', '-', $name);
        $name = trim($name, "-");
        $existing = $this->getRepository('XRealmAppBundle:Group')->findOneBySlug($name);
        $counter = 1;
        $base = $name;
        while(!empty($existing))
        {
            $counter++;
            $name = $base . '-' . $counter;
            $existing = $this->getRepository('XRealmAppBundle:Group')->findOneBySlug($name);
        }
        return $name;
    }

    protected function prepareShow(Group $group)
    {
        if(in_array($group->getSlug(),array(
            'xrealm-bugreport',
        )))
        {
            $isXRealm = true;
        }
        else
        {
            $isXRealm = false;
        }

        if(!$this->get('blizz_character')->getSelected())
        {
            $self = false;
        }
        else
        {
            $self = $this->getRepository('XRealmAppBundle:GroupInvolvedCharacter')->findOneBy(array(
                'group' => $group,
                'character' => $this->get('blizz_character')->getSelected()->getData(),
            ));
        }

        $chars = $this->getRepository('XRealmAppBundle:GroupInvolvedCharacter')->findBy(array(
            'group' => $group,
        ));

        $isLeader = false;
        $isMember = false;
        $isAssist = false;
        if($this->get('blizz_character')->getSelected())
        {
            if(!empty($self) && $self->getStatus() == GroupInvolvedCharacter::STATUS_JOINED && $this->get('blizz_character')->getSelected()->getData()->getIsVerified())
            {
                $isMember = true;
                if($self->getRank() == GroupInvolvedCharacter::RANK_ASSIST)
                {
                    $isAssist = true;
                }
                if($self->getRank() == GroupInvolvedCharacter::RANK_LEADER)
                {
                    $isAssist = true;
                    $isLeader = true;
                }
            }
        }

        $now = new \DateTime();
        $session = $this->getRequest()->getSession();
        $since = new \DateTime($session->get('activity_view_' . $group->getId(), $now->format('Y-m-d H:i:s')));
        $unseenActivities = $this->getRepository('XRealmAppBundle:Activity')->findNewSince($group, $since);
        

        return array(
            'group' => $group,
            'self'  => $self,
            'isLeader'  => $isLeader,
            'isMember'  => $isMember,
            'isAssist'  => $isAssist,
            'isXRealm'  => $isXRealm,
            'unseenActivities'  => $unseenActivities,
            'memberCount'   => count($chars),
        );
    }

    protected function getGroupByIdentifier($identifier) {
        $group = $this->getRepository('XRealmAppBundle:Group')->findOneBy(array(
            'slug'  => $identifier,
        ));
        if(empty($group))
        {
            $group = $this->getRepository('XRealmAppBundle:Group')->findOneBy(array(
                'id' => $identifier,
            ));
        }
        return $group;
    }

    public function leaveGroupAction($slug)
    {
        $group = $this->getGroupByIdentifier($slug);
        if(empty($group))
        {
            $this->addFlash('danger', $this->get('translator')->trans('error.groupnotexists'));
            return $this->redirect($this->generateUrl('index'));
        }
        $return = $this->prepareShow($group);

        if($return['isMember'])
        {
            $self = $return['self'];
            if($self->getRank() == GroupInvolvedCharacter::RANK_LEADER)
            {
                $this->addFlash('danger', $this->get('translator')->trans('error.leaveowngroup'));
                return $this->redirect($this->generateUrl('groups_index'));
            }
            $this->addNotification($self->getCharacter(), $group, 'notification.left_group');
            $this->remove($self, true);
            $this->addFlash('info', $this->get('translator')->trans('message.leftgroup', array(
                '%group%'   => $group->getName(),
            )));
        }
        return $this->redirect($this->generateUrl('groups_index'));

    }

    public function showAction(Request $request, $slug)
    {

        $group = $this->getGroupByIdentifier($slug);
        //$this->getRepository('XRealmAppBundle:Group')->updateRanking($group);
        if(empty($group))
        {
            $this->addFlash('danger', $this->get('translator')->trans('error.groupnotexists'));
            return $this->redirect($this->generateUrl('index'));
        }
        $return = $this->prepareShow($group);
        if(!$return['isMember'] && !$group->getIsPublic())
        {
            $this->addFlash('danger', $this->get('translator')->trans('error.nogrouppermission'));
            return $this->redirect($this->generateUrl('index'));
        }
        
        if($return['isMember'])
        {
            $stickyFilter = array(
                'group'     => $group,
                'isSticky'  => true,
            );
        }
        else {
            $stickyFilter = array(
                'group'     => $group,
                'isSticky'  => true,
                'isPublic'  => true,
            );
        }
        $return['stickies'] = $this->getRepository('XRealmAppBundle:Activity')->findBy($stickyFilter, array('postedAt' => 'DESC'), 5);
         
        $return['raids'] = $this->getRepository('XRealmAppBundle:Raid')->findBy(array(
            'isActive'  => true,
        ));
        $return['progress'] = $this->getRepository('XRealmAppBundle:Group')->getProgress($group);
        return $this->render('XRealmAppBundle:Pages/Group:show.html.twig', $return);
    }

    public function showMembersAction(Request $request, $slug)
    {
        $group = $this->getGroupByIdentifier($slug);
        if(empty($group))
        {
            $this->addFlash('danger', $this->get('translator')->trans('error.groupnotexists'));
            return $this->redirect($this->generateUrl('index'));
        }
        $return = $this->prepareShow($group);
        if(!$return['isMember'] && !$group->getIsPublic())
        {
            $this->addFlash('danger', $this->get('translator')->trans('error.nogrouppermission'));
            return $this->redirect($this->generateUrl('index'));
        }

        $members = $this->getRepository('XRealmAppBundle:GroupInvolvedCharacter')->findMembers($group);
        $return['members'] = $members;

        $realmstats = array();
        $rolechart = array(
            BlizzCharacter::ROLE_HEAL => 0,
            BlizzCharacter::ROLE_MELEE => 0,
            BlizzCharacter::ROLE_RANGE => 0,
            BlizzCharacter::ROLE_TANK => 0,
        );
        foreach($members as $member)
        {
            $realmid = $member->getCharacter()->getRealm()->getId();
            if($member->getCharacter()->getRole())
            {
                $rolechart[$member->getCharacter()->getRole()]++;
            }
            
            
            if(empty($realmstats[$realmid]))
            {
                $realmstats[$realmid] = array(
                    "value" => 0,
                    "label" => $member->getCharacter()->getRealm()->getName(),
                );
            }
            $realmstats[$realmid]['value']++;
        }
        $chartdata = array(
            'labels' => array(),
            'datasets'  => array(
                0 => array(
                    "label"                 => "Realms",
                    "fillColor"             => "rgba(244,137,32,0.2)",
                    "strokeColor"           => "rgba(244,137,32,1)",
                    "pointColor"            => "rgba(244,137,32,1)",
                    "pointStrokeColor"      => "#fff",
                    "pointHighlightFill"    => "#fff",
                    "pointHighlightStroke"  => "rgba(244,137,32,1)",
                    'data'                  => array(),
                ),
            ),
        );
        $rolechartdata = array(
            'labels' => array(),
            'datasets'  => array(
                0 => array(
                    "label"                 => "Roles",
                    "fillColor"             => "rgba(244,137,32,0.2)",
                    "strokeColor"           => "rgba(244,137,32,1)",
                    "pointColor"            => "rgba(244,137,32,1)",
                    "pointStrokeColor"      => "#fff",
                    "pointHighlightFill"    => "#fff",
                    "pointHighlightStroke"  => "rgba(244,137,32,1)",
                    'data'                  => array(),
                ),
            ),
        );


        foreach($realmstats as $chartelement)
        {
            $chartdata['labels'][] = $chartelement['label'];
            $chartdata['datasets'][0]['data'][] = $chartelement['value'];
        }
        foreach($rolechart as $key => $value)
        {
            $rolechartdata['labels'][] =  $this->get('translator')->trans('role.' . $key);
            $rolechartdata['datasets'][0]['data'][] = $value;
        }

       
        $return['rolestats']  = json_encode($rolechartdata);
        $return['realmstats'] = json_encode($chartdata);

        if($return['isAssist'])
        {
            $character = new BlizzCharacter();
            $addCharacterForm = $this->createForm(new AddCharacterType(), $character, array(
                'action' => $this->generateUrl('groups_add_character', array('slug' => $group->getSlug())) . '#addcharacter',
            ));
            $addCharacterForm->handleRequest($request);

            if($addCharacterForm->isValid() && $addCharacterForm->isSubmitted())
            {
                $success = $this->inviteCharacter($character, $addCharacterForm, $group);
                if($success)
                {
                    return $this->returnGroupPage($group, 'members');
                }
            }
            $return['addCharacterForm'] = $addCharacterForm->createView();
        }
        return $this->render('XRealmAppBundle:Pages/Group:members.html.twig', $return);

    }

    public function showActivityAction(Request $request, $slug)
    {
        $group = $this->getGroupByIdentifier($slug);
        if(empty($group))
        {
            $this->addFlash('danger', $this->get('translator')->trans('error.groupnotexists'));
            return $this->redirect($this->generateUrl('index'));
        }
        $return = $this->prepareShow($group);
        $session = $this->getRequest()->getSession();

        $now = new \DateTime();
        $session->set('activity_view_' . $group->getId(), $now->format('Y-m-d H:i:s'));

        if($return['isMember'] or $return['isXRealm'])
        {
            $post = new ActivityPost();
            if($return['isMember'])
            {
                $post->setIsPublic(false);
            }
            else
            {
                $post->setIsPublic(true);
            }
            $post->setIsSticky(false);
            $form = $this->createForm(new ActivityPostType(), $post, array(
                'isAssist'  => $return['isAssist'],
                'action' => $this->generateUrl('groups_post_activity', array(
                    'slug'  => $group->getSlug(),
                )),
                'isXRealm'  => $return['isXRealm'],
            ));

            $form->handleRequest($request);
            if($form->isValid() && $form->isSubmitted())
            {
                $content = $form->get('message')->getData();
                if(strlen(trim($content)) <= 0)
                {
                    $form->get('message')->addError(new FormError(
                        $this->get('translator')->trans('error.emptypostvalue')
                    ));
                }
                else
                {
                    $post->setGroup($group);
                    $post->setCharacter($this->get('blizz_character')->getSelected()->getData());
                    $now = new \DateTime();
                    $post->setPostedAt($now);
                    $this->persist($post, true);
                    return $this->returnGroupPage($group, 'activity');
                }
            }

            $return['form'] = $form->createView();
        }
        $activities = $this->getRepository('XRealmAppBundle:Activity')->findPaginatedActivity($group, 1, $return['isMember']);
         
        $return['activities'] = $activities;
        return $this->render('XRealmAppBundle:Pages/Group:activity.html.twig', $return);
    }

    public function editAction(Request $request, $slug)
    {
        $group = $this->getGroupByIdentifier($slug);
        if(empty($group))
        {
            $this->addFlash('danger', $this->get('translator')->trans('error.groupnotexists'));
            return $this->redirect($this->generateUrl('index'));
        }
        $return = $this->prepareShow($group);
        if(!$return['isLeader'])
        {
            return $this->returnGroupPage($group);
        }

        $form = $this->createForm(new GroupType(), $group, array(
            'action' => $this->generateUrl('groups_options_save', array(
                'slug'  => $group->getSlug(),
            )),
            'type'   => 'edit',
        ));
        $progress = $this->getRepository('XRealmAppBundle:Group')->getProgress($group);
        $raids = $this->getRepository('XRealmAppBundle:Raid')->findAll();
        $this->buildGroupProgressForm($form, $raids, $progress);

        $form->handleRequest($request);

        if($form->isValid() && $form->isSubmitted())
        {
            $bindedProgress = $this->bindProgressInformation($group, $raids, $form, $progress);
            if($bindedProgress)
            {
                $this->persist($group, true);
                $this->addFlash('info', $this->get('translator')->trans('message.savedchanges'));
            }
            else
            {
                $return['showProgressEdit'] = true;
            }
            
        }
        $return['form'] = $form->createView();
        $return['raids'] = $raids;
        return $this->render('XRealmAppBundle:Pages/Group:edit.html.twig', $return);
    }

    public function showEventsAction(Request $request, $slug)
    {
        $group = $this->getGroupByIdentifier($slug);
        if(empty($group))
        {
            $this->addFlash('danger', $this->get('translator')->trans('error.groupnotexists'));
            return $this->redirect($this->generateUrl('index'));
        }
        $return = $this->prepareShow($group);
        if(!$return['isMember'])
        {
            return $this->returnGroupPage($group);
        }
        $events = $this->getRepository('XRealmAppBundle:Event')->findEvents($group);
        $return['events'] = array();
        $first = false;
        $last = false;
        foreach($events as $event)
        {
            if($first == false || $event->getStartAt() < $first)
            {
                $first = clone $event->getStartAt();
            }
            if($last == false || $event->getStartAt() > $last)
            {
                $last = clone $event->getStartAt();
            }
            $return['events'][$event->getStartAt()->format('Y-m-d') . '-' . $event->getId()] = $event;
        }
        if(!empty($events))
        {
            while($first <= $last)
            {
                $first->modify('+1day');
                if(3 == (int) $first->format('N'))
                {
                    $first->setTime(3, 0, 0);
                    $return['events'][$first->format('Y-m-d')] = 'idreset';
                }
            }
            ksort($return['events']);
        }


        if($return['isAssist'])
        {
            $event = new Event();
            $event->setGroup($group);
            $nextDate = new \DateTime();
            $nextDate->modify('+1day');
            $nextDate->setTime(18, 00, 00);
            $event->setStartAt($nextDate);
            $event->setCreatedBy($this->get('blizz_character')->getSelected()->getData());
            $form = $this->createForm(new EventType(), $event, array(
                'action' => $this->generateUrl('groups_create_event', array(
                    'slug'  => $group->getSlug(),
                )),
                'datetime_format'    => $this->get('translator')->trans('datetime_format'),
            ));
            $form->handleRequest($request);
            
            
            if($form->isValid() && $form->isSubmitted())
            {
                $error = false;
                $endAt = $form->get('endAt')->getData();
                $startAt = $form->get('startAt')->getData();
                if(!empty($endAt) && $endAt instanceof \DateTime)
                {
                    $diff = $startAt->diff($endAt);
                    if($diff->y > 0 || $diff->m > 0 || $diff->d > 0 || ($startAt->format('d') != $endAt->format('d')))
                    {
                        $form->get('endAt')->addError(new FormError($this->get('translator')->trans('error.enddate_sameday')));
                        $error = true;
                    }
                }
                if(!$error)
                {
                    $this->persist($event, true);
                    $this->addFlash('success', $this->get('translator')->trans('message.event_created'));
                    return $this->returnGroupPage($group, 'events');
                }

            }
            $return['form'] = $form->createView();
        }
        $return['event_forms']  = array();
        foreach($return['events'] as $event)
        {
            if($event instanceof Event)
            {
                $status = 0;
                foreach($event->getInvolvedCharacters() as $char)
                {
                    if($char->getCharacter()->getId() == $this->get('blizz_character')->getSelected()->getData()->getId())
                    {
                        $status = $char->getStatus();
                    }
                    break;
                }

                $existing = $this->getRepository('XRealmAppBundle:EventInvolvedCharacter')->findOneBy(array(
                    'character' => $this->get('blizz_character')->getSelected()->getData(),
                    'event' => $event,
                ));

                if(empty($existing))
                {
                    $createMember = new EventInvolvedCharacter();
                    $createMember->setEvent($event);
                    $createMember->setCharacter($this->get('blizz_character')->getSelected()->getData());
                }
                else
                {
                    $createMember = $existing;
                }
                $eventForm = $this->createForm(new EventInvolvedType(), $createMember,array(
                    'action' => $this->generateUrl('calendar_join_event', array(
                        'id'  => $event->getId(),
                        
                    )),
                    'redirect' => 'group',
                ));
                $return['event_forms'][$event->getId()] = array(
                    'status' => $status,
                    'form' => $eventForm->createView(),
                );

            }
        }
        return $this->render('XRealmAppBundle:Pages/Group:events.html.twig', $return);
    }

    public function deleteEventAction(Event $event)
    {
        $group = $event->getGroup();
        $return = $this->prepareShow($group);
        if($return['isAssist'])
        {
            foreach($event->getInvolvedCharacters() as $member)
            {
                $this->remove($member);
            }
            $this->remove($event);
            $this->flush();
            $this->addFlash('success', $this->get('translator')->trans('message.eventdeleted'));
        }
        
        return $this->returnGroupPage($group, 'events');
    }

    public function removeMemberAction(GroupInvolvedCharacter $member)
    {
        $group = $member->getGroup();
        
        $return = $this->prepareShow($group);
        if(
            !$return['isAssist'] ||
            $member->getRank() >= $return['self']->getRank()
        )
        {
            $this->addFlash('danger', $this->get('translator')->trans('error.group.kickpermission'));
            return $this->returnGroupPage($group, 'members');
        }
        $this->addNotification($member->getCharacter(), $member->getGroup(), 'notification.removed_from_group');
        $this->remove($member, true);
        
        $this->addFlash('success', $this->get('translator')->trans('message.group.kicked', array(
            '%name%'  =>  $member->getCharacter()->getName(),
            '%realm%' => $member->getCharacter()->getRealm()->getName(),
        )));
        return $this->returnGroupPage($group, 'members');

    }

    
    protected function inviteCharacter(BlizzCharacter $character, $form, Group $group)
    {
        $existingCharacter = $this->getRepository('XRealm\\AppBundle\\Entity\\BlizzCharacter')->findOneBy(array(
            'name'  => $character->getName(),
            'realm' => $character->getRealm(),
        ));
        if($existingCharacter)
        {
            $existingInGroup = $this->getRepository('XRealmAppBundle:GroupInvolvedCharacter')->findOneBy(array(
                'group' => $group,
                'character' => $existingCharacter,
            ));
            if($existingInGroup) {
                $form->get('name')->addError(new FormError($this->get('translator')->trans('error.alreadyingroup')));
                return false;
            }
            else
            {
                $character = $existingCharacter;
            }
        }
        else
        {
            $characterData = $this->get('blizz_api')->get('character', array(
                'character' => $character->getName(),
                'realm'     => $character->getRealm()->getSlug(),
                'fields'    => 'items,talents,progression',
            ));
            $attemps = 3;
            while($attemps > 0 && isset($characterData['status']) || isset($characterData['reason']) || !isset($characterData['name']))
            {
                $attemps--;
                $characterData = $this->get('blizz_api')->get('character', array(
                    'character' => $character->getName(),
                    'realm'     => $character->getRealm()->getSlug(),
                    'fields'    => 'items,talents,progression',
                ));
            }

            if(isset($characterData['status']) || isset($characterData['reason']) || !isset($characterData['name']))
            {
                $form->get('name')->addError(new FormError($this->get('translator')->trans('error.character_dontexists')));
                return false;
            }
            $creator = new \XRealm\Component\BlizzCharacter\CharacterCreater();
            $creator->bindData($characterData, $character);
            $this->persist($character, true);
            $this->flush($character);
            
        }
        $member = new GroupInvolvedCharacter();
        $member->setCharacter($character);
        $member->setGroup($group);
        $member->setRank(GroupInvolvedCharacter::RANK_MEMBER);
        $member->setStatus(GroupInvolvedCharacter::STATUS_INVITED);
        $this->persist($member, true);
        $this->flush($member);
        $this->addFlash('success', $this->get('translator')->trans('message.invitedcharacter', array(
            '%name%' => $character->getName(),
            '%realm%' => $character->getRealm()->getName(),
        )));
        
        $this->addNotification($character, $group, 'notification.invited_to_group');
        
        
        return true;
    }

    protected function addNotification(BlizzCharacter $character, Group $group, $message)
    {
        $notification = new ActivityNotification();
        $notification->setCharacter($character);
        $notification->setGroup($group);
        $notification->setIsSticky(false);
        $notification->setIsPublic(false);
        $notification->setMessage($message);
        $now = new \DateTime();
        $notification->setPostedAt($now);
        $this->persist($notification, true);
        return true;
    }

    protected function buildGroupProgressForm($form, $raids, $progress)
    {
        foreach($raids as $raid)
        {
            foreach($raid->getBosses() as $boss)
            {
                $data = GroupBossStatus::STATUS_NONE;
                if(isset($progress[$boss->getBlizzId()]))
                {
                    $data = $progress[$boss->getBlizzId()]->getStatus();
                }
                $form->add('boss_' . $boss->getBlizzId(), 'choice', array(
                    'choices'   => array(
                        GroupBossStatus::STATUS_NONE => 'game.boss.status.' . GroupBossStatus::STATUS_NONE,
                        GroupBossStatus::STATUS_NORMAL => 'game.boss.status.' . GroupBossStatus::STATUS_NORMAL,
                        GroupBossStatus::STATUS_HEROIC => 'game.boss.status.' . GroupBossStatus::STATUS_HEROIC,
                        GroupBossStatus::STATUS_MYTHIC => 'game.boss.status.' . GroupBossStatus::STATUS_MYTHIC,
                    ),
                    'mapped' => false,
                    'label' => 'game.boss.' . $boss->getSlug(),
                    'data'  => $data,
                ));
            }
        }
        return $form;
    }

    protected function bindProgressInformation($group, $raids, $form, $progress)
    {
        $members = $this->getRepository('XRealmAppBundle:GroupInvolvedCharacter')->findBy(array(
            'status'    => GroupInvolvedCharacter::STATUS_JOINED,
            'group'     => $group,
        ));
        $progression = array();
        foreach($members as $member)
        {
            $charProgress = $member->getCharacter()->getProgression();
            if($charProgress)
            {
                foreach($charProgress as $raid)
                {
                    if(!empty($raid['bosses']))
                    {
                        foreach($raid['bosses'] as $boss)
                        {
                            if(empty($progression[$boss['id']]) || !is_array($progression[$boss['id']]))
                            {
                                $progression[$boss['id']] = array(
                                    GroupBossStatus::STATUS_NORMAL => 0,
                                    GroupBossStatus::STATUS_HEROIC => 0,
                                    GroupBossStatus::STATUS_MYTHIC => 0,
                                );
                            }
                            if($boss['normalKills'] > 0)
                            {
                                $progression[$boss['id']][GroupBossStatus::STATUS_NORMAL]++;
                            }
                            if($boss['heroicKills'] > 0)
                            {
                                $progression[$boss['id']][GroupBossStatus::STATUS_HEROIC]++;
                            }
                            if($boss['mythicKills'] > 0)
                            {
                                $progression[$boss['id']][GroupBossStatus::STATUS_MYTHIC]++;
                            }
                        }
                    }
                }
            }
           
        }
        $error = 0;
        $minProgress = 10;
        foreach($raids as $raid)
        {
            foreach($raid->getBosses() as $boss)
            {
                if(isset($progress[$boss->getBlizzId()]))
                {
                    $bossProgress = $progress[$boss->getBlizzId()];
                }
                else
                {
                    $bossProgress = new GroupBossStatus();
                    $bossProgress->setGroup($group);
                    $bossProgress->setBoss($boss);
                }
                $bossId = $boss->getBlizzId();
                $setStatus = $form->get('boss_' . $bossId)->getData();


                if((empty($progression[$bossId][$setStatus]) || $progression[$bossId][$setStatus] < $minProgress) && $setStatus != GroupBossStatus::STATUS_NONE)
                {
                    $form->get('boss_' . $bossId)->addError(new FormError(
                        $this->get('translator')->trans('form.error.notenoughprogress', array(
                            '%count%'   => $minProgress,
                            '%progress%'=> empty($progression[$bossId][$setStatus]) ? 0 : $progression[$bossId][$setStatus],
                        ))
                    ));
                    $error++;
                }
                if($error == 0)
                {
                    $bossProgress->setStatus($setStatus);

                    $this->persist($bossProgress);
                }
            }
        }
        if($error == 0)
        {
            $this->flush();
        }
        $this->getRepository('XRealmAppBundle:Group')->updateRanking($group);
        return empty($error);
    }

    public function revokeassistAction(GroupInvolvedCharacter $member)
    {
        $group = $member->getGroup();
        $return = $this->prepareShow($group);
        if($return['isLeader'])
        {
            if($member->getRank() == GroupInvolvedCharacter::RANK_ASSIST && $member->getStatus() == GroupInvolvedCharacter::STATUS_JOINED)
            {
                $member->setRank(GroupInvolvedCharacter::RANK_MEMBER);
                $this->addFlash('info', $this->get('translator')->trans('message.revokeassist',array(
                    '%name%' => $member->getCharacter()->getName(),
                    '%realm%' => $member->getCharacter()->getRealm()->getName(),
                )));
                $this->persist($member, true);
            }
            else
            {
                $this->addFlash('danger', $this->get('translator')->trans('error.notableformember'));
            }
        }
        else
        {
            $this->addFlash('danger', $this->get('translator')->trans('error.groupermission'));
        }
        return $this->returnGroupPage($group, 'members');
    }

    public function makeassistAction(GroupInvolvedCharacter $member)
    {
        $group = $member->getGroup();
        $return = $this->prepareShow($group);
        if($return['isLeader'])
        {
            if($member->getRank() == GroupInvolvedCharacter::RANK_MEMBER && $member->getStatus() == GroupInvolvedCharacter::STATUS_JOINED)
            {
                $member->setRank(GroupInvolvedCharacter::RANK_ASSIST);
                $this->addFlash('info', $this->get('translator')->trans('message.makeassist',array(
                    '%name%' => $member->getCharacter()->getName(),
                    '%realm%' => $member->getCharacter()->getRealm()->getName(),
                )));
                $this->persist($member, true);
            }
            else
            {
                $this->addFlash('danger', $this->get('translator')->trans('error.notableformember'));
            }
        }
        else
        {
            $this->addFlash('danger', $this->get('translator')->trans('error.groupermission'));
        }
        return $this->returnGroupPage($group, 'members');
    }

    public function makeleaderAction(GroupInvolvedCharacter $member)
    {
        $group = $member->getGroup();
        $return = $this->prepareShow($group);
        if($return['isLeader'])
        {
            if($member->getRank() != GroupInvolvedCharacter::RANK_LEADER && $member->getStatus() == GroupInvolvedCharacter::STATUS_JOINED)
            {
                $self = $return['self'];
                $self->setRank(GroupInvolvedCharacter::RANK_ASSIST);
                $member->setRank(GroupInvolvedCharacter::RANK_LEADER);
                $this->addFlash('info', $this->get('translator')->trans('message.makeleader',array(
                    '%name%' => $member->getCharacter()->getName(),
                    '%realm%' => $member->getCharacter()->getRealm()->getName(),
                )));
                $this->persist($self, true);
                $this->persist($member, true);
                $this->addNotification($member->getCharacter(), $group, 'notification.makeleader');
            }
            else
            {
                $this->addFlash('danger', $this->get('translator')->trans('error.notableformember'));
            }
        }
        else
        {
            $this->addFlash('danger', $this->get('translator')->trans('error.groupermission'));
        }
        return $this->returnGroupPage($group, 'members');
    }

    public function deleteActivityAction(ActivityPost $post)
    {
        $group = $post->getGroup();
        $return = $this->prepareShow($group);
        $poster = $post->getCharacter();

        if($return['isAssist'] || $poster->getId() == $return['self']->getCharacter()->getId())
        {
            $this->remove($post, true);

        }
        else
        {
            $this->addFlash('danger', $this->get('translator')->trans('error.groupermission'));
        }
        return $this->returnGroupPage($group, 'activity');
    }

    public function toggleActivityStickyAction(ActivityPost $post)
    {
        $group = $post->getGroup();
        $return = $this->prepareShow($group);
        $poster = $post->getCharacter();

        if($return['isAssist'])
        {
            if($post->getIsSticky())
            {
                $post->setIsSticky(false);
            }
            else
            {
                $post->setIsSticky(true);
            }
            $this->persist($post, true);

        }
        else
        {
            $this->addFlash('danger', $this->get('translator')->trans('error.groupermission'));
        }
        return $this->returnGroupPage($group, 'activity');
    }

    public function postCommentAction(Request $request, Activity $activity)
    {
        $group = $activity->getGroup();
        $return = $this->prepareShow($group);
        if($return['isMember'])
        {
            $comment = new \XRealm\AppBundle\Entity\Comment();
            $form = $this->createFormBuilder($comment, array(
                'csrf_protection'   => false,
            ));
            $form->add('message', 'text');
            $form = $form->getForm();
            $form->handleRequest($request);
            if ($form->isValid() && $this->get('blizz_character')->getSelected()) {
                $message = $form->get('message')->getData();
                if(strlen(trim($message)) > 0)
                {
                    $comment->setActivity($activity);
                    $comment->setCharacter($this->get('blizz_character')->getSelected()->getData());
                    $comment->setPostedAt(new \DateTime());
                    $comment->setMessage($message);
                    $this->persist($comment, true);
                }

            }
        }
        return $this->returnGroupPage($group, 'activity', 'activity-' . $activity->getId());
    }

    public function deleteCommentAction(\XRealm\AppBundle\Entity\Comment $comment)
    {
        $group = $comment->getActivity()->getGroup();
        $return = $this->prepareShow($group);
        if($this->get('blizz_character')->getSelected())
        {
            if($this->get('blizz_character')->getSelected()->getData()->getId() == $comment->getCharacter()->getId() || $return['isAssist'])
            {
                $this->remove($comment, true);
            }
        }
        return $this->returnGroupPage($group, 'activity', 'activity-' . $comment->getActivity()->getId());
    }
}
