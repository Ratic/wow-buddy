<?php
// src/xRealm/AppBundle/DataFixtures/ORM/LoadRolesData.php
namespace XRealm\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use XRealm\UserBundle\Entity\Role;

class LoadRoleData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
    * {@inheritDoc}
    */
    public function load(ObjectManager $manager)
    {
        foreach (array(
            /* Nutzerrechte Auftragsverwaltung */
            'ROLE_USER'     => array('ROLE_USER'),
			'ROLE_MOD'     => array('ROLE_MOD'),
			'ROLE_ADMIN'     => array('ROLE_ADMIN'),
			'ROLE_DEVELOPER'     => array('ROLE_DEVELOPER'),
        ) as $roleName => $roleKey)
        {
            $role = new Role();
            $role->setName($roleName);
            $role->setRole($roleKey[0]);

            $manager->persist($role);
            $manager->flush();

            $this->addReference($roleKey[0], $role);
        }
    }

    /**
    * {@inheritDoc}
    */
    public function getOrder()
    {
        return 1;
    }
}