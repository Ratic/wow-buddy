<?php
// src/XRealm/UserBundle/Entity/User.php
namespace XRealm\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * XRealm\UserBundle\Entity\BlizzCharater
 *
 * @ORM\Table(name="blizz_character")
 * @ORM\Entity()
 */
class BlizzCharacter
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="characters")
     */
	private $user;

    /**
     * @ORM\Column(name="is_verified", type="boolean")
     */
    private $isVerified;

    /**
     * @ORM\Column(name="is_selected", type="boolean")
     */
    private $isSelected;

    /**
     * @ORM\Column(type="string", length=25, unique=true)
     */
	private $name;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
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
     * @ORM\ManyToOne(targetEntity="BlizzRealm", inversedBy="blizzCharacters")
     */
	private $realm;




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
     * Set user
     *
     * @param \XRealm\UserBundle\Entity\User $user
     * @return Character
     */
    public function setUser(\XRealm\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \XRealm\UserBundle\Entity\User 
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
     * Set realm
     *
     * @param \XRealm\UserBundle\Entity\BlizzRealm $realm
     * @return BlizzCharacter
     */
    public function setRealm(\XRealm\UserBundle\Entity\BlizzRealm $realm = null)
    {
        $this->realm = $realm;

        return $this;
    }

    /**
     * Get realm
     *
     * @return \XRealm\UserBundle\Entity\BlizzRealm 
     */
    public function getRealm()
    {
        return $this->realm;
    }
}
