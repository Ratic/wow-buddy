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

class GroupRepository extends EntityRepository
{
    public function getAllowedExportGroups(BlizzCharacter $character)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('g.id, g.name, g.slug, g.groupRating');
        $qb->from('XRealmAppBundle:Group', 'g', 'g.id');
        $qb->join('XRealmAppBundle:GroupInvolvedCharacter', 'gc', Expr\Join::WITH, 'gc.group = g');
        $qb->where('gc.character = :character AND gc.status = :status');
        $qb->setParameter('character', $character);
        $qb->setParameter('status', GroupInvolvedCharacter::STATUS_JOINED);
        $qb->groupBy('g.id');
        return $qb->getQuery()->getArrayResult(Query::HYDRATE_ARRAY);
        
    }
    public function findGroupsForCharacter(BlizzCharacter $character, $status = GroupInvolvedCharacter::STATUS_JOINED)
    {
        $qb = $this->createQueryBuilder('g');
        
        $qb->join('XRealmAppBundle:GroupInvolvedCharacter', 'gc', Expr\Join::WITH, 'gc.group = g');

        $qb->where('gc.character = :character AND gc.status = :status');
        $qb->setParameter('character', $character);
        $qb->setParameter('status', $status);
        $qb->groupBy('g.id');
        $groups = $qb->getQuery()->getResult();
        
        foreach($groups as $group)
        {
            $group->setDetail('role', $this->findGroupRoles($group));
            $group->setDetail('leader', $this->findGroupLeader($group));
        }

        return  $groups;
    }
    
    public function findGroupRoles(Group $group)
    {

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('count(c1.id) as role' . BlizzCharacter::ROLE_TANK
                . ', count(c2.id) as role' . BlizzCharacter::ROLE_HEAL
                . ', count(c3.id) as role' . BlizzCharacter::ROLE_MELEE
                . ', count(c4.id) as role' . BlizzCharacter::ROLE_RANGE);
        $qb->from('XRealmAppBundle:GroupInvolvedCharacter', 'gic');
        $qb->leftJoin('XRealmAppBundle:BlizzCharacter', 'c1', Expr\Join::WITH, 'c1 = gic.character AND c1.role = :role1');
        $qb->leftJoin('XRealmAppBundle:BlizzCharacter', 'c2', Expr\Join::WITH, 'c2 = gic.character AND c2.role = :role2');
        $qb->leftJoin('XRealmAppBundle:BlizzCharacter', 'c3', Expr\Join::WITH, 'c3 = gic.character AND c3.role = :role3');
        $qb->leftJoin('XRealmAppBundle:BlizzCharacter', 'c4', Expr\Join::WITH, 'c4 = gic.character AND c4.role = :role4');
        
        
        $qb->setParameters(array(
            'role1' => BlizzCharacter::ROLE_TANK,
            'role2' => BlizzCharacter::ROLE_HEAL,
            'role3' => BlizzCharacter::ROLE_MELEE,
            'role4' => BlizzCharacter::ROLE_RANGE,
        ));
        $qb->where('gic.group = :group');
        $qb->setParameter('group', $group);
        $roles = $qb->getQuery()->setMaxResults(1)->getArrayResult();
        return $roles[0];
    }
    
    public function findGroupLeader(Group $group)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('c, r');
        $qb->from('XRealmAppBundle:BlizzCharacter', 'c');
        $qb->join('XRealmAppBundle:GroupInvolvedCharacter', 'gic', Expr\Join::WITH, 'gic.character = c');
        $qb->join('c.realm', 'r');
        $qb->where('gic.group = :group and gic.rank = :leader');
        
        $qb->setParameters(array(
            'group' => $group,
            'leader'=> GroupInvolvedCharacter::RANK_LEADER,
        ));
        return $qb->getQuery()->getSingleResult();
    }

    public function getProgress(Group $group)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('p, b');
        $qb->from('XRealmAppBundle:GroupBossStatus', 'p');
        $qb->join('p.boss', 'b');
        $qb->where('p.group = :group');
        $qb->setParameter('group', $group);
        $result = $qb->getQuery()->getResult();
        $return = array();
        foreach($result as $row)
        {
            $return[$row->getBoss()->getBlizzId()] = $row;
        }
        return $return;
    }

    public function  updateRanking(Group $group)
    {
        $ranking = 0;
        $bosses = $group->getBosses();
        foreach($bosses as $boss)
        {
            if($boss->getBoss()->getRaid()->getIsActive())
            {
                $ranking += $boss->getBoss()->getDifficulty() * $boss->getStatus();
            }
        }
        $group->setGroupRating($ranking);
        $this->getEntityManager()->persist($group);
        $this->getEntityManager()->flush($group);
    }
}