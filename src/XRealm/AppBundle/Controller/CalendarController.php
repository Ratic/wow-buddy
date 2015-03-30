<?php
namespace XRealm\AppBundle\Controller;

use XRealm\AppBundle\Controller\Controller as BaseController;
use Symfony\Component\HttpFoundation\Request;
use XRealm\AppBundle\Entity\Event;
use XRealm\AppBundle\Entity\EventInvolvedCharacter;
use XRealm\AppBundle\Form\Type\EventInvolvedType;
use XRealm\Component\Calendar\CalendarBuilder;

class CalendarController extends BaseController
{
    public function indexAction(Request $request, $month)
    {   
        $builder = new CalendarBuilder($month);
        if($this->get('blizz_character')->getSelected())
        {
            $character = $this->get('blizz_character')->getSelected()->getData();
            $events = $this->getRepository('XRealmAppBundle:Event')->findDetailedEvents($character, $builder->getStartDate(), $builder->getEndDate());
            $builder->addEvents($events);
        }

        
        return $this->render('XRealmAppBundle:Pages:calendar.html.twig', array(
            'calendar'  => $builder->createCalendar(),
            'event_forms'   => $this->getEventForms($events),
        ));
    }

    protected function getEventForms($events)
    {
        $forms = array();
        foreach($events as $data)
        {
            $event = $data['event'];
            
            if($event instanceof Event)
            {
                $status = $data['self_status'];

                if($status != 0)
                {
                    $existing = $this->getRepository('XRealmAppBundle:EventInvolvedCharacter')->findOneBy(array(
                        'character' => $this->get('blizz_character')->getSelected()->getData(),
                        'event' => $event,
                    ));
                }
                else
                {
                    $existing = false;
                }


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
                $eventForm = $this->createForm(new EventInvolvedType(), $createMember, array(
                    'action' => $this->generateUrl('calendar_join_event', array(
                        'id'  => $event->getId(),
                    )),
                    'redirect' => 'calendar',
                ));
                $forms[$event->getId()] = array(
                    'status' => $status,
                    'form' => $eventForm->createView(),
                );

            }

        }
        return $forms;
    }



    
    public function joinEventAction(Request $request, Event $event)
    {
        $group = $event->getGroup();
        if($this->get('blizz_character')->getSelected()) {
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

            $eventForm = $this->createForm(new EventInvolvedType(), $createMember ,array(
                'action' => $this->generateUrl('calendar_join_event', array(
                    'id'  => $event->getId(),
                )),
            ));

            $eventForm->handleRequest($request);
            $error = false;
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

            if(empty($self))
            {
                $this->addFlash('danger', $this->get('translator')->trans('error.not_an_event_member'));
                $error = true;
            }


            if($eventForm->isSubmitted() && $eventForm->isValid() && !$error)
            {
                if(strlen($createMember->getComment()) > 140)
                {
                    $createMember->setComment(substr($createMember->getComment(), 0, 140) . '...');
                }
                $this->persist($createMember, true);
                $redirectTo = $eventForm->get('redirect')->getData();

                if($createMember->getStatus() == EventInvolvedCharacter::STATUS_ACCEPTED)
                {
                    $this->addFlash('info', $this->get('translator')->trans('message.acceptedevent', array(
                        '%name%'    => $event->getTitle(),
                        '%date%'    => $event->getStartAt()->format('d.m.Y'),
                    )));
                }
                else
                {
                    $this->addFlash('info', $this->get('translator')->trans('message.declinedevent', array(
                        '%name%'    => $event->getTitle(),
                        '%date%'    => $event->getStartAt()->format('d.m.Y'),
                    )));
                }


                if(!in_array($redirectTo, array('calendar', 'group')))
                {
                    $redirectTo = 'calendar';
                }
                if($redirectTo == 'calendar')
                {
                    return $this->redirect($this->generateUrl('calendar_index', array(
                        'month' => $event->getStartAt()->format('Y-m'),
                    )));
                }
                else
                {
                    return $this->returnGroupPage($group, 'events');
                }

            }
        }
       

        return $this->returnGroupPage($group, 'events');
    }

}
