<?php
namespace XRealm\Component\Calendar;

use XRealm\AppBundle\Entity\Event;
use XRealm\Component\Calendar\CalendarBuilder;
use XRealm\Component\Calendar\CalendarWeek;

class Calendar {
    protected $builder;
    protected $weeks;

    public function __construct(CalendarBuilder $builder, \DateTime $startDate, \DateTime $endDate)
    {
        $this->builder = $builder;
        
        $this->weeks = array();
        $dateIterator = clone $startDate;
        while($dateIterator < $endDate)
        {
            $week = clone $dateIterator;
            $this->weeks[$week->format('W')] = new CalendarWeek($week);
            $dateIterator->modify('+1week');
        }
        ksort($this->weeks);
    }

    public function addEvent($event)
    {
        if(!empty($this->weeks[$event['event']->getStartAt()->format('W')]))
        {
            $this->weeks[$event['event']->getStartAt()->format('W')]->addEvent($event);
        }
    }
    public function getWeeks()
    {
        return $this->weeks;
    }

    public function setActiveMonth($month)
    {
        foreach($this->weeks as $week)
        {
            $week->setActiveMonth($month);
        }
    }
    public function getLabelDate()
    {
        return $this->builder->getLabelDate();
    }

    public function getNextMonth()
    {
        $return = clone $this->builder->getLabelDate();
        $return->modify('+1month');
        return $return;
    }
    public function getPrevMonth()
    {
        $return = clone $this->builder->getLabelDate();
        $return->modify('-1month');
        return $return;
    }
}