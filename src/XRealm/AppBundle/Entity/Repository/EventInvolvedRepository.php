<?php
// src/Acme/AppBundle/Entity/UserRepository.php
namespace XRealm\AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use XRealm\AppBundle\Entity\GroupInvolvedCharacter;
use XRealm\AppBundle\Entity\BlizzCharacter;
use Doctrine\ORM\Query\Expr;
use XRealm\AppBundle\Entity\Group;
use Doctrine\ORM\Query;

class EventInvolvedRepository extends EntityRepository
{
    public function getAllowedExportMembers($event_ids)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('m.id, m.status, m.comment, e.id as event_id, c.id as character_id');
        $qb->from('XRealmAppBundle:EventInvolvedCharacter', 'm', 'm.id');
        $qb->join('m.event', 'e');
        $qb->join('m.character', 'c');
        
        $qb->where('m.event IN(:ids)');
        $qb->setParameter(':ids', $event_ids);
        $qb->groupBy('m.id');
        return $qb->getQuery()->getArrayResult(Query::HYDRATE_ARRAY);
        
    }
}