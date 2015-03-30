<?php
namespace XRealm\Component\Calendar;

use XRealm\AppBundle\Entity\Event;
use XRealm\Component\Calendar\Calendar;
use XRealm\Component\Calendar\CalendarWeek;
use XRealm\Component\Calendar\CalendarColumn;

class CalendarBuilder {

    protected $startDate;
    protected $endDate;
    protected $month;
    protected $today;

    protected $data;

    protected $labelDate;



    public function __construct($month)
    {
        $this->data = array();
        $this->setDateRange($month);
    }

    protected function setDateRange($month)
    {

        $this->month = $month;
        $this->labelDate = new \DateTime($month . '-01');
        $this->today = new \DateTime();
        
        $month = new \DateTime($month . '-01');
        $weekday = (int) $month->format('N');
        $this->startDate = clone $month;
        $this->startDate->modify('-' . $weekday  + 1 . 'days');
        $this->startDate->setTime(0, 0, 0);

        $month = new \DateTime($month->format('Y-m-t'));
        $weekday = (int) $month->format('N');
        $this->endDate = clone $month;
        $this->endDate->modify('+' . ( 7 - $weekday)  . 'days');
        $this->endDate->setTime(23, 59, 59);

        
    }

    public function addEvent($event)
    {
        $this->data[] = $event;
        return $this;
    }

    public function addEvents($events)
    {
        foreach($events as $event)
        {
            $this->addEvent($event);
        }
        return $this;
    }

    public function getStartDate()
    {
        return $this->startDate;
    }

    public function getEndDate()
    {
        return $this->endDate;
    }

    public function createCalendar()
    {
        $calendar = new Calendar($this, $this->startDate, $this->endDate);
        foreach($this->data as $event)
        {
            $startDate = $event['event']->getStartAt();
            if($startDate > $this->startDate && $startDate < $this->endDate)
            {
               $calendar->addEvent($event);
            }
        }
        $calendar->setActiveMonth($this->month);
        return $calendar;
    }

    public function getLabelDate()
    {
        return $this->labelDate;
    }
    
}