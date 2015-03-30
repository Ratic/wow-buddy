<?php
// src/xRealm/AppBundle/DataFixtures/ORM/LoadUserData.php
namespace XRealm\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use XRealm\UserBundle\Entity\User;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('atic');
        $user->setEmail('atic@smartape.de');

        $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);

        $user->setPassword($encoder->encodePassword('atic', $user->getSalt()));

        $user->addRole($this->getReference('ROLE_DEVELOPER'));
        $manager->persist($user);
    }

    /**
    * {@inheritDoc}
    */
    public function getOrder()
    {
        return 2;
    }
}