<?php
// src/XRealm/AppBundle/Entity/User.php
namespace XRealm\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * XRealm\AppBundle\Entity\Group
 *
 * @ORM\Table(name="raidgroup")
 * @ORM\Entity(repositoryClass="XRealm\AppBundle\Entity\Repository\GroupRepository")
 */
class Group
{
    protected $details = false;
    
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=25)
     */
    private $name;
    
    /**
     * @ORM\OneToMany(targetEntity="GroupInvolvedCharacter", mappedBy="group") 
     */
    private $involvedCharacters;
    
    /**
     * @ORM\OneToMany(targetEntity="Activity", mappedBy="group") 
     */
    private $activities;

    /**
     * @ORM\OneToMany(targetEntity="Event", mappedBy="group")
     */
    private $events;

    /**
     * @ORM\OneToMany(targetEntity="GroupBossStatus", mappedBy="group", indexBy="id")
     */
    private $bosses;
    
    /**
     * @ORM\Column(name="is_public", type="boolean")
     */
    private $isPublic;

    /**
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=29)
     */
    private $slug;

    /**
     * @ORM\Column(type="integer")
     */
    private $groupRating;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    
    public function setDetail($key, $value)
    {
        if(!is_array($this->details))
        {
            $this->details = array();
        }
        $this->details[$key] = $value;
    }
    
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Group
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->involvedCharacters = new \Doctrine\Common\Collections\ArrayCollection();
        $this->groupRating = 0;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set isPublic
     *
     * @param boolean $isPublic
     * @return Group
     */
    public function setIsPublic($isPublic)
    {
        $this->isPublic = $isPublic;

        return $this;
    }

    /**
     * Get isPublic
     *
     * @return boolean 
     */
    public function getIsPublic()
    {
        return $this->isPublic;
    }

    /**
     * Add involvedCharacters
     *
     * @param \XRealm\AppBundle\Entity\GroupInvolvedCharacter $involvedCharacters
     * @return Group
     */
    public function addInvolvedCharacter(\XRealm\AppBundle\Entity\GroupInvolvedCharacter $involvedCharacters)
    {
        $this->involvedCharacters[] = $involvedCharacters;

        return $this;
    }

    /**
     * Remove involvedCharacters
     *
     * @param \XRealm\AppBundle\Entity\GroupInvolvedCharacter $involvedCharacters
     */
    public function removeInvolvedCharacter(\XRealm\AppBundle\Entity\GroupInvolvedCharacter $involvedCharacters)
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
     * Set description
     *
     * @param string $description
     * @return Group
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Group
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Add bosses
     *
     * @param \XRealm\AppBundle\Entity\GroupBossStatus $bosses
     * @return Group
     */
    public function addBoss(\XRealm\AppBundle\Entity\GroupBossStatus $bosses)
    {
        $this->bosses[] = $bosses;

        return $this;
    }

    /**
     * Remove bosses
     *
     * @param \XRealm\AppBundle\Entity\GroupBossStatus $bosses
     */
    public function removeBoss(\XRealm\AppBundle\Entity\GroupBossStatus $bosses)
    {
        $this->bosses->removeElement($bosses);
    }

    /**
     * Get bosses
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBosses()
    {
        return $this->bosses;
    }

    /**
     * Add activities
     *
     * @param \XRealm\AppBundle\Entity\Activity $activities
     * @return Group
     */
    public function addActivity(\XRealm\AppBundle\Entity\Activity $activities)
    {
        $this->activities[] = $activities;

        return $this;
    }

    /**
     * Remove activities
     *
     * @param \XRealm\AppBundle\Entity\Activity $activities
     */
    public function removeActivity(\XRealm\AppBundle\Entity\Activity $activities)
    {
        $this->activities->removeElement($activities);
    }

    /**
     * Get activities
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getActivities()
    {
        return $this->activities;
    }

    /**
     * Add events
     *
     * @param \XRealm\AppBundle\Entity\Event $events
     * @return Group
     */
    public function addEvent(\XRealm\AppBundle\Entity\Event $events)
    {
        $this->events[] = $events;

        return $this;
    }

    /**
     * Remove events
     *
     * @param \XRealm\AppBundle\Entity\Event $events
     */
    public function removeEvent(\XRealm\AppBundle\Entity\Event $events)
    {
        $this->events->removeElement($events);
    }

    /**
     * Get events
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * Set groupRating
     *
     * @param integer $groupRating
     * @return Group
     */
    public function setGroupRating($groupRating)
    {
        $this->groupRating = $groupRating;

        return $this;
    }

    /**
     * Get groupRating
     *
     * @return integer 
     */
    public function getGroupRating()
    {
        return (int) $this->groupRating;
    }
}
