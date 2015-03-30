<?php
// src/XRealm/AppBundle/Entity/User.php
namespace XRealm\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * XRealm\AppBundle\Entity\Group
 *
 * @ORM\Table(name="event")
 * @ORM\Entity(repositoryClass="XRealm\AppBundle\Entity\Repository\EventRepository")
 */
class Event
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Group", inversedBy="events")
     */
    protected $group;

    /**
     * @ORM\Column(name="start_at", type="datetime")
     */
    protected $startAt;

    /**
     * @ORM\Column(name="end_at", type="datetime", nullable=true)
     */
    protected $endAt;

    /**
     * @ORM\Column(type="string", length=125)
     */
    private $title;

    /**
     * @ORM\OneToMany(targetEntity="EventInvolvedCharacter", mappedBy="event")
     */
    private $involvedCharacters;

    /**
     * @ORM\ManyToOne(targetEntity="BlizzCharacter")
     */
    protected $createdBy;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    
    public function getRoleCount()
    {
        $roles = array();
        if(!empty($this->involvedCharacters) && is_array($this->involvedCharacters))
        {
            foreach($this->involvedCharacters as $char)
            {
                $role = $char->getCharacter()->getRole();
                $roles[$role]++;
            }
        }
        return $roles;
    }

    /**
     * Set startAt
     *
     * @param \DateTime $startAt
     * @return Event
     */
    public function setStartAt($startAt)
    {
        $this->startAt = $startAt;

        return $this;
    }

    /**
     * Get startAt
     *
     * @return \DateTime 
     */
    public function getStartAt()
    {
        return $this->startAt;
    }

    /**
     * Set endAt
     *
     * @param \DateTime $endAt
     * @return Event
     */
    public function setEndAt($endAt)
    {
        $this->endAt = $endAt;

        return $this;
    }

    /**
     * Get endAt
     *
     * @return \DateTime 
     */
    public function getEndAt()
    {
        return $this->endAt;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Event
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set group
     *
     * @param \XRealm\AppBundle\Entity\Group $group
     * @return Event
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
     * Constructor
     */
    public function __construct()
    {
        $this->involvedCharacters = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add involvedCharacters
     *
     * @param \XRealm\AppBundle\Entity\EventInvolvedCharacter $involvedCharacters
     * @return Event
     */
    public function addInvolvedCharacter(\XRealm\AppBundle\Entity\EventInvolvedCharacter $involvedCharacters)
    {
        $this->involvedCharacters[] = $involvedCharacters;

        return $this;
    }

    /**
     * Remove involvedCharacters
     *
     * @param \XRealm\AppBundle\Entity\EventInvolvedCharacter $involvedCharacters
     */
    public function removeInvolvedCharacter(\XRealm\AppBundle\Entity\EventInvolvedCharacter $involvedCharacters)
    {
        $this->involvedCharacters->removeElement($involvedCharacters);
    }

    /**
     * Get involvedCharacters
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getInvolvedCharacters()
    {
        return $this->involvedCharacters;
    }

    /**
     * Set createdBy
     *
     * @param \XRealm\AppBundle\Entity\BlizzCharacter $createdBy
     * @return Event
     */
    public function setCreatedBy(\XRealm\AppBundle\Entity\BlizzCharacter $createdBy = null)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return \XRealm\AppBundle\Entity\BlizzCharacter 
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }
}
