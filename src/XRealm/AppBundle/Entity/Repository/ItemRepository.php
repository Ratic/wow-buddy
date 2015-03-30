<?php
// src/Acme/AppBundle/Entity/UserRepository.php
namespace XRealm\AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\Query;

class ItemRepository extends EntityRepository
{
    public function findOneByIdent($id, $context)
    {
        $qb = $this->createQueryBuilder('i');
        $qb->where('i.blizzId = :id');
        $qb->setParameter('id', $id);
        if(!in_array($context, array('raid-heroic', 'raid-normal', 'raid-mythic')))
        {
            $context = false;
        }
        if(!$context)
        {
            $qb->andWhere('(i.context IS NULL OR i.context = :emptycontext OR i.context = :defaultcontext)');
            $qb->setParameter('defaultcontext', 'raid-normal');
            $qb->setParameter('emptycontext', '');
        }
        else
        {
            $qb->andWhere('i.context = :context');
            $qb->setParameter('context', $context);
        }
        $result = $qb->getQuery()->getResult();
        if(empty($result))
        {
            return null;
        }
        else
        {
            return $result[0];
        }

    }
    
}