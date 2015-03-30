<?php
// src/XRealm/AppBundle/Entity/User.php
namespace XRealm\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * XRealm\AppBundle\Entity\BlizzCharater
 *
 * @ORM\Table(name="blizz_character")
 * @ORM\Entity()
 */
class BlizzCharacter
{
    const ROLE_TANK = 1;
    const ROLE_MELEE = 2;
    const ROLE_RANGE = 3;
    const ROLE_HEAL = 4;
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="blizzCharacters")
     */
    private $user;
    
    /**
     * @ORM\Column(type="integer", length=4)
     */
    private $role;
    
    /**
     * @ORM\Column(name="is_verified", type="boolean")
     */
    private $isVerified;

    /**
     * @ORM\Column(name="is_selected", type="boolean")
     */
    private $isSelected;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $thumbnail;

    /**
     * @ORM\Column(type="integer", length=25)
     */
    private $achievmentPoints;

    /**
     * @ORM\Column(type="integer", length=1)
     */
    private $gender;

    /**
     * @ORM\Column(type="integer", length=4)
     */
    private $level;

    /**
     * @ORM\Column(type="integer", length=4)
     */
    private $race;
    
    /**
     * @ORM\Column(type="integer", length=4)
     */
    private $class;

    /**
     * @ORM\ManyToOne(targetEntity="BlizzRealm", inversedBy="blizzCharacters")
     */
    private $realm;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $talents;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $items;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $stats;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $progression;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $verifyItems;
    
    /**
     * @ORM\OneToMany(targetEntity="EventInvolvedCharacter", mappedBy="character")
     */
    private $events;

    /**
     * @ORM\OneToMany(targetEntity="GroupInvolvedCharacter", mappedBy="character")
     */
    private $groups;


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
     * Set isVerified
     *
     * @param boolean $isVerified
     * @return Character
     */
    public function setIsVerified($isVerified)
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * Get isVerified
     *
     * @return boolean 
     */
    public function getIsVerified()
    {
        return $this->isVerified;
    }
    
    /**
     * Set verifyItems
     *
     * @param string $verifyItems
     * @return Character
     */
    public function setVerifyItems($verifyItems)
    {
        if(is_array($verifyItems))
        {
            $verifyItems = implode(',',$verifyItems);
        }
        $this->verifyItems = $verifyItems;

        return $this;
    }

    /**
     * Get verifyItems
     *
     * @return string
     */
    public function getVerifyItems()
    {
        return explode(',', $this->verifyItems);
    }

    /**
     * Set user
     *
     * @param \XRealm\AppBundle\Entity\User $user
     * @return Character
     */
    public function setUser(\XRealm\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \XRealm\AppBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set isSelected
     *
     * @param boolean $isSelected
     * @return Character
     */
    public function setIsSelected($isSelected)
    {
        $this->isSelected = $isSelected;

        return $this;
    }

    /**
     * Get isSelected
     *
     * @return boolean 
     */
    public function getIsSelected()
    {
        return $this->isSelected;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return BlizzCharacter
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
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
     * Set thumbnail
     *
     * @param string $thumbnail
     * @return BlizzCharacter
     */
    public function setThumbnail($thumbnail)
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    /**
     * Get thumbnail
     *
     * @return string 
     */
    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    /**
     * Set achievmentPoints
     *
     * @param integer $achievmentPoints
     * @return BlizzCharacter
     */
    public function setAchievmentPoints($achievmentPoints)
    {
        $this->achievmentPoints = $achievmentPoints;

        return $this;
    }

    /**
     * Get achievmentPoints
     *
     * @return integer 
     */
    public function getAchievmentPoints()
    {
        return $this->achievmentPoints;
    }
    
    /**
     * Set arole
     *
     * @param integer $achievmentPoints
     * @return BlizzCharacter
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return integer 
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set gender
     *
     * @param integer $gender
     * @return BlizzCharacter
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return integer 
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set level
     *
     * @param integer $level
     * @return BlizzCharacter
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return integer 
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set race
     *
     * @param integer $race
     * @return BlizzCharacter
     */
    public function setRace($race)
    {
        $this->race = $race;

        return $this;
    }

    /**
     * Get race
     *
     * @return integer 
     */
    public function getRace()
    {
        return $this->race;
    }
    
    /**
     * Set class
     *
     * @param integer $class
     * @return BlizzCharacter
     */
    public function setClass($class)
    {
        $this->class = $class;

        return $this;
    }

    /**
     * Get class
     *
     * @return integer 
     */
    public function getClass()
    {
        return $this->class;
    }
    
    /**
     * Set realm
     *
     * @param \XRealm\AppBundle\Entity\BlizzRealm $realm
     * @return BlizzCharacter
     */
    public function setRealm(\XRealm\AppBundle\Entity\BlizzRealm $realm = null)
    {
        $this->realm = $realm;

        return $this;
    }

    /**
     * Get realm
     *
     * @return \XRealm\AppBundle\Entity\BlizzRealm 
     */
    public function getRealm()
    {
        return $this->realm;
    }

    /**
     * Set items
     *
     * @param string $items
     * @return BlizzCharacter
     */
    public function setItems($items)
    {
        if(is_array($items) || $items instanceof \stdClass)
        {
            $items = json_encode($items);
        }
        $this->items = $items;
        
        return $this;
    }

    /**
     * Get items
     *
     * @return string 
     */
    public function getItems()
    {
        return json_decode($this->items, true);
    }

    /**
     * Set items
     *
     * @param string $items
     * @return BlizzCharacter
     */
    public function setTalents($talents)
    {
        if(is_array($talents) || $talents instanceof \stdClass)
        {
            $talents = json_encode($talents);
        }
        $this->talents = $talents;

        return $talents;
    }

    /**
     * Get items
     *
     * @return string
     */
    public function getTalents()
    {
        $talents = json_decode($this->talents, true);
        foreach($talents as &$tree)
        {
            usort($tree['talents'], function($a, $b){
                if($a['tier'] == $b['tier'])
                {
                    return 0;
                }
                return ($a['tier'] < $b['tier']) ? -1 : 1;

            });
        }
        return $talents;
    }

    /**
     * Set items
     *
     * @param string $items
     * @return BlizzCharacter
     */
    public function setStats($stats)
    {
        if(is_array($stats) || $stats instanceof \stdClass)
        {
            $stats = json_encode($stats);
        }
        $this->stats = $stats;

        return $this;
    }

    /**
     * Get items
     *
     * @return string
     */
    public function getStats()
    {
        return json_decode($this->stats, true);
    }

    /**
     * Set progression
     *
     * @param string $progression
     * @return BlizzCharacter
     */
    public function setProgression($progression)
    {
        if(is_array($progression) || $progression instanceof \stdClass)
        {
            $progression = json_encode($progression);
        }
        $this->progression = $progression;

        return $this;
    }

    /**
     * Get items
     *
     * @return string
     */
    public function getProgression()
    {
        return json_decode($this->progression, true);
    }
    
    public function getAvailableRoles()
    {
        switch($this->class)
        {
            case 1: //warri
            case 6: //dk
                return array(self::ROLE_MELEE, self::ROLE_TANK);
            case 2: //pala
                return array(self::ROLE_HEAL, self::ROLE_MELEE, self::ROLE_TANK);
            case 3://hunter
            case 8://mage
            case 9://warlock
                return array(self::ROLE_RANGE);
            case 4: // Rouge
                return array(self::ROLE_MELEE);
            case 5: //Priest
                return array(self::ROLE_HEAL, self::ROLE_RANGE);
            case 7: //shame
                return array(self::ROLE_HEAL, self::ROLE_MELEE, self::ROLE_RANGE);
            case 10: //monk
                return array(self::ROLE_HEAL, self::ROLE_MELEE, self::ROLE_TANK);
            case 11: //druid
                return array(self::ROLE_MELEE, self::ROLE_TANK, self::ROLE_HEAL, self::ROLE_RANGE);
                
                        
               
                
        }
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->groups = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add groups
     *
     * @param \XRealm\AppBundle\Entity\GroupInvolvedCharacter $groups
     * @return BlizzCharacter
     */
    public function addGroup(\XRealm\AppBundle\Entity\GroupInvolvedCharacter $groups)
    {
        $this->groups[] = $groups;

        return $this;
    }

    /**
     * Remove groups
     *
     * @param \XRealm\AppBundle\Entity\GroupInvolvedCharacter $groups
     */
    public function removeGroup(\XRealm\AppBundle\Entity\GroupInvolvedCharacter $groups)
    {
        $this->groups->removeElement($groups);
    }

    /**
     * Get groups
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGroups()
    {
        return $this->groups;
    }
}
