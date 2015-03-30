<?php
// src/XRealm/AppBundle/Entity/User.php
namespace XRealm\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * XRealm\AppBundle\Entity\Group
 *
 * @ORM\Table(name="group_progress")
 * @ORM\Entity(repositoryClass="XRealm\AppBundle\Entity\Repository\GroupInvolvedRepository")
 */
class GroupBossStatus
{
    const STATUS_NONE = 0;
    const STATUS_NORMAL = 1;
    const STATUS_HEROIC = 2;
    const STATUS_MYTHIC = 3;
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Group", inversedBy="bosses")
     */
    private $group;

    /**
     * @ORM\ManyToOne(targetEntity="Boss")
     */
    private $boss;

    /**
     * @ORM\Column(name="status", type="integer", length=4)
     */
    private $status;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return GroupBossStatus
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set group
     *
     * @param \XRealm\AppBundle\Entity\Group $group
     * @return GroupBossStatus
     */
    public function setGroup(\XRealm\AppBundle\Entity\Group $group = null)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * Get group
     *
     * @return \XRealm\AppBundle\Entity\Group 
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * Set boss
     *
     * @param \XRealm\AppBundle\Entity\Boss $boss
     * @return GroupBossStatus
     */
    public function setBoss(\XRealm\AppBundle\Entity\Boss $boss = null)
    {
        $this->boss = $boss;

        return $this;
    }

    /**
     * Get boss
     *
     * @return \XRealm\AppBundle\Entity\Boss 
     */
    public function getBoss()
    {
        return $this->boss;
    }
}
