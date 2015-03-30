<?php
namespace XRealm\Component\Calendar;

use XRealm\AppBundle\Entity\Event;
use XRealm\Component\Calendar\CalendarWeek;

class CalendarColumn {
    protected $events;
    protected $day;
    protected $week;

    public function __construct(\DateTime $day)
    {
        $this->day = $day;
        $this->events = array();
    }

    public function addEvent($event)
    {
        if($event['event']->getStartAt()->format('Y-m-d') == $this->day->format('Y-m-d'))
        {
            $this->events[$event['event']->getStartAt()->format('Y-m-d-H-i') . '-' . $event['event']->getId()] = $event;
        }
        ksort($this->events);
    }

    public function getEvents()
    {
        return $this->events;
    }

    public function getDate()
    {
        return $this->day;
    }

    public function setWeek(CalendarWeek $week)
    {
        $this->week = $week;
    }

    public function getIsActive()
    {
        if(!empty($this->week) && !empty($this->week->getActiveMonth()))
        {
            if($this->day->format('Y-m') == $this->week->getActiveMonth())
            {
                return true;
            }

        }
        return false;
    }
    public function getIsToday()
    {
        $now = new \DateTime();
        if($this->day->format('Y-m-d') == $now->format('Y-m-d'))
        {
            return true;
        }
        return false;
    }
}