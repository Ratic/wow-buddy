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

class GroupInvolvedRepository extends EntityRepository
{
    public function getAllowedExportMembers($group_ids)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('m.id, m.status, m.rank, g.id as group_id, c.id as character_id');
        $qb->from('XRealmAppBundle:GroupInvolvedCharacter', 'm', 'm.id');
        $qb->join('m.group', 'g');
        $qb->join('m.character', 'c');
        
        $qb->where('m.group IN(:ids)');
        $qb->setParameter(':ids', $group_ids);
        $qb->groupBy('m.id');
        return $qb->getQuery()->getArrayResult(Query::HYDRATE_ARRAY);
        
    }
    
    
    public function findMembers(Group $group)
    {
        $qb = $this->createQueryBuilder('gic');
        $qb->select('gic, c, r');
        $qb->join('gic.character', 'c');
        $qb->join('c.realm', 'r');
        $qb->where('gic.group = :group');
        $qb->setParameter('group', $group);
        $qb->orderBy('gic.status' ,'DESC');
        $qb->addOrderBy('gic.rank' ,'DESC');
        $qb->addOrderBy('c.name' , 'ASC');
        return $qb->getQuery()->getResult();
    }

    public function findForPermission(BlizzCharacter $character,  $permCharacter)
    {
        $qb = $this->createQueryBuilder('gic');
        $qb->select('gic, c, r');
        $qb->join('gic.character', 'c');
        $qb->join('c.realm', 'r');#
        $qb->join('gic.group', 'g');
        $qb->where('gic.character = :character');
        $qb->setParameter('character', $character);
        if($permCharacter instanceof BlizzCharacter)
        {
            $qb->andWhere('g.isPublic = 1 OR EXISTS(SELECT gic2 FROM XRealmAppBundle:GroupInvolvedCharacter gic2 WHERE gic2.group = g and gic2.character = :permcharacter)');
            $qb->setParameter('permcharacter', $permCharacter);
        }
        else
        {
            $qb->andWhere('g.isPublic = 1');
        }

        return $qb->getQuery()->getResult();
    }
    
}