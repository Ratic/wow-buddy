<?php
// src/Acme/AppBundle/Entity/UserRepository.php
namespace XRealm\AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use XRealm\AppBundle\Entity\GroupInvolvedCharacter;
use XRealm\AppBundle\Entity\BlizzCharacter;
use Doctrine\ORM\Query\Expr;
use XRealm\AppBundle\Entity\Group;
use XRealm\AppBundle\Entity\EventInvolvedCharacter;
use Doctrine\ORM\Query;

class EventRepository extends EntityRepository
{
    public function getAllowedExportEvents(BlizzCharacter $character)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('e.id, e.startAt, e.endAt, e.title, g.id as group_id');
        $qb->from('XRealmAppBundle:Event', 'e', 'e.id');
        $qb->join('e.group', 'g');
        $qb->join('XRealmAppBundle:GroupInvolvedCharacter', 'gc', Expr\Join::WITH, 'gc.group = e.group');
        
        $qb->where('gc.character = :character AND gc.status = :status and e.startAt > :now');
        $qb->setParameter('character', $character);
        $qb->setParameter('now', new \DateTime());
        $qb->setParameter('status', GroupInvolvedCharacter::STATUS_JOINED);
        $qb->groupBy('e.id');
        return $qb->getQuery()->getArrayResult(Query::HYDRATE_ARRAY);
        
    }
    
    public function findEvents(Group $group)
    {
        $now = new \DateTime();
        $qb = $this->createQueryBuilder('e');
        $qb->select('e, eic');
        $qb->leftJoin('e.involvedCharacters', 'eic');
        $qb->where('e.group = :group');
        $qb->setParameter('group', $group);
        
        $qb->andWhere('e.startAt >= CURRENT_DATE() OR (e.endAt IS NOT NULL AND e.endAt >= CURRENT_DATE())');
        $qb->orderBy('e.startAt' ,'ASC');
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function findForCharacter($character, \DateTime $startDate, \DateTime $endDate)
    {
        $now = new \DateTime();
        $qb = $this->createQueryBuilder('e');
        $qb->select('e, eic');
        $qb->leftJoin('e.involvedCharacters', 'eic');
        $qb->join('e.group', 'g');
        $qb->leftJoin('g.involvedCharacters' ,'gic');

        $qb->orderBy('e.startAt' ,'ASC');
        $qb->where('gic.character = :character');
        $qb->andWhere('e.startAt > :start AND e.startAt < :end');
        $qb->setParameters(array(
            'start' => $startDate,
            'end'   => $endDate,
            'character' => $character,
        ));

        $result = $qb->getQuery()->getResult();
        
        return $result;
    }

    public function findDetailedEvents($character, $startDate = false, $endDate = false)
    {
        $now = new \DateTime();
        $parameters = array(
            'character' => $character,
        );


        $qb = $this->createQueryBuilder('e');
        $qb->select('e as event, g');
        $qb->addSelect('(
            SELECT sgi.status FROM XRealmAppBundle:EventInvolvedCharacter sgi
            WHERE sgi.event = e AND sgi.character = :character
        ) as self_status');
        $qb->addSelect('(
            SELECT COUNT(accepts.id) FROM XRealmAppBundle:EventInvolvedCharacter accepts
            WHERE accepts.event = e AND accepts.status = :accept
        ) as accepted');
        $qb->addSelect('(
            SELECT COUNT(declines.id) FROM XRealmAppBundle:EventInvolvedCharacter declines
            WHERE declines.event = e AND declines.status = :declined
        ) as declined');
        $parameters['accept'] = EventInvolvedCharacter::STATUS_ACCEPTED;
        $parameters['declined'] = EventInvolvedCharacter::STATUS_DECLINED;

        $qb->join('e.group', 'g');



        $expr = $qb->expr()->exists('
            SELECT gic FROM XRealmAppBundle:GroupInvolvedCharacter gic
            WHERE gic.character = :character AND gic.group = g AND gic.status = 2
        ');
        $qb->where($expr);


        $qb->orderBy('e.startAt' ,'ASC');

        
        
        if(!empty($startDate) && !empty($endDate))
        {
            if($startDate instanceof \DateTime)
            {
                $parameters['startDate'] = $startDate;
            }
            else
            {
                $parameters['startDate'] = new \DateTime($startDate);
            }
            if($endDate instanceof \DateTime)
            {
                $parameters['endDate'] = $endDate;
            }
            else
            {
                $parameters['endDate'] = new \DateTime($endDate);
            }
            $qb->andWhere('e.startAt > :startDate AND e.startAt < :endDate');
        }


        $qb->setParameters($parameters);

        $qb->groupBy('e.id');

        $result = $qb->getQuery()->getResult();
        return $result;
    }
}