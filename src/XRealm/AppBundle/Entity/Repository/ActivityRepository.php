<?php
// src/Acme/AppBundle/Entity/UserRepository.php
namespace XRealm\AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use XRealm\AppBundle\Entity\Group;

class ActivityRepository extends EntityRepository
{
    public function findPaginatedActivity(Group $group, $page = 1, $isMember)
    {
        $page = $page - 1;
        $results = 100;
        $first = $page * $results;
        $qb = $this->createQueryBuilder('a');
        $qb->select('a, c, cc');
        //$qb->orderBy('a.isSticky', 'DESC');
        $qb->orderBy('a.postedAt', 'DESC');
        $qb->where('a.group = :group');
        $qb->leftJoin('a.comments', 'c');
        $qb->leftJoin('c.character', 'cc');
        if(!$isMember)
        {
            $qb->andWhere('a.isPublic = 1');
        }
        $qb->setParameter('group', $group);
        $qb ->setFirstResult($first)
            ->setMaxResults($results);

        $query = $qb->getQuery();
        return new Paginator($query, $fetchJoinCollection = true);
    }

    public function findNewSince(Group $group, \DateTime $since)
    {
        $qb = $this->createQueryBuilder('a');
        $qb->where('a.postedAt > :since');
        $qb->andWhere('a.group = :group');
        $qb->setParameter('group', $group);
        $qb->setParameter('since', $since);
        $result = $qb->getQuery()->getArrayResult();
        if(!$result)
        {
            return 0;
        }
        else
        {
            return count($result);
        }
    }
}