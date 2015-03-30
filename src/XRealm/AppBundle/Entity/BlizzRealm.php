<?php
// src/XRealm/AppBundle/Entity/BlizzRealm.php
namespace XRealm\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * XRealm\AppBundle\Entity\BlizzRealm
 *
 * @ORM\Table(name="blizz_realm")
 * @ORM\Entity()
 */
class BlizzRealm
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

	/**
     * @ORM\Column(name="name", type="string", length=50)
     */
	private $name;

	/**
     * @ORM\Column(name="type", type="string", length=3)
     */
	private $type;

	/**
     * @ORM\Column(name="battlegroup", type="string", length=50)
     */
	private $battlegroup;

	/**
     * @ORM\Column(name="timezone", type="string", length=50)
     */
	private $timezone;

	/**
     * @ORM\Column(name="slug", type="string", length=50)
     */
	private $slug;

	/**
     * @ORM\Column(name="locale", type="string", length=5)
     */
	private $locale;

    /**
     * @ORM\OneToMany(targetEntity="BlizzCharacter", mappedBy="realm")
     * @ORM\JoinColumn(name="realm_id", referencedColumnName="id")
     */
	private $blizzCharacters;

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
     * Set name
     *
     * @param string $name
     * @return Realm
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
     * Set locale
     *
     * @param string $locale
     * @return Realm
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * Get locale
     *
     * @return string 
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Realm
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
     * Set type
     *
     * @param string $type
     * @return Realm
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set battlegroup
     *
     * @param string $battlegroup
     * @return Realm
     */
    public function setBattlegroup($battlegroup)
    {
        $this->battlegroup = $battlegroup;

        return $this;
    }

    /**
     * Get battlegroup
     *
     * @return string 
     */
    public function getBattlegroup()
    {
        return $this->battlegroup;
    }

    /**
     * Set timezone
     *
     * @param string $timezone
     * @return Realm
     */
    public function setTimezone($timezone)
    {
        $this->timezone = $timezone;

        return $this;
    }

    /**
     * Get timezone
     *
     * @return string 
     */
    public function getTimezone()
    {
        return $this->timezone;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->blizzCharacters = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add blizzCharacters
     *
     * @param \XRealm\AppBundle\Entity\BlizzCharacter $blizzCharacters
     * @return BlizzRealm
     */
    public function addBlizzCharacter(\XRealm\AppBundle\Entity\BlizzCharacter $blizzCharacters)
    {
        $this->blizzCharacters[] = $blizzCharacters;

        return $this;
    }

    /**
     * Remove blizzCharacters
     *
     * @param \XRealm\AppBundle\Entity\BlizzCharacter $blizzCharacters
     */
    public function removeBlizzCharacter(\XRealm\AppBundle\Entity\BlizzCharacter $blizzCharacters)
    {
        $this->blizzCharacters->removeElement($blizzCharacters);
    }

    /**
     * Get blizzCharacters
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBlizzCharacters()
    {
        return $this->blizzCharacters;
    }
}
