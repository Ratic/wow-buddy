<?php
namespace XRealm\Component\Calendar;

use XRealm\AppBundle\Entity\Event;

class CalendarWeek {

    protected $weekNumber;
    protected $startDate;
    protected $endDate;
    protected $cloumns;
    protected $activeMonth;

    public function __construct(\DateTime $date)
    {
        $this->cloumns = array();
        $weekday = (int) $date->format('N');
        $this->startDate = clone $date;
        $this->startDate->modify('-' . $weekday  + 1 . 'days');
        $this->startDate->setTime(0, 0, 0);
        $this->endDate = clone $date;
        
        $this->endDate->modify('+' . ( 7 - $weekday)  . 'days');
        $this->endDate->setTime(23, 59, 59);

        $dateIterator = clone $this->startDate;
        while($dateIterator < $this->endDate)
        {
            $day = clone $dateIterator;
            $this->cloumns[$dateIterator->format('Y-m-d')] = new CalendarColumn($day);
            $this->cloumns[$dateIterator->format('Y-m-d')]->setWeek($this);
            $dateIterator->modify('+1day');
        }
        ksort($this->cloumns);
        
    }
    public function setWeek($weekNumber)
    {
        $this->weekNumber = $weekNumber;
    }

    public function addEvent($event)
    {
        if(!empty($this->cloumns[$event['event']->getStartAt()->format('Y-m-d')]))
        {
            $this->cloumns[$event['event']->getStartAt()->format('Y-m-d')]->addEvent($event);
        }
    }
    
    public function getColumns()
    {
        return $this->cloumns;
    }

    public function setActiveMonth($month)
    {
        $this->activeMonth = $month;
    }

    public function getActiveMonth()
    {
        if(empty($this->activeMonth))
        {
            return false;
        }
        return $this->activeMonth;
    }


}