<?php
// src/XRealm/AppBundle/Entity/User.php
namespace XRealm\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * XRealm\AppBundle\Entity\Group
 *
 * @ORM\Table(name="raid")
 * @ORM\Entity()
 */
class Raid
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=125)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="Boss", mappedBy="raid")
     */
    private $bosses;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(name="blizz_id", type="integer", length=125)
     */
    private $blizzId;

    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->bosses = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * @return Raid
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
     * Add bosses
     *
     * @param \XRealm\AppBundle\Entity\Boss $bosses
     * @return Raid
     */
    public function addBoss(\XRealm\AppBundle\Entity\Boss $bosses)
    {
        $this->bosses[] = $bosses;

        return $this;
    }

    /**
     * Remove bosses
     *
     * @param \XRealm\AppBundle\Entity\Boss $bosses
     */
    public function removeBoss(\XRealm\AppBundle\Entity\Boss $bosses)
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
     * Set slug
     *
     * @param string $slug
     * @return Raid
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
     * Set isActive
     *
     * @param boolean $isActive
     * @return Raid
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean 
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set blizzId
     *
     * @param integer $blizzId
     * @return Raid
     */
    public function setBlizzId($blizzId)
    {
        $this->blizzId = $blizzId;

        return $this;
    }

    /**
     * Get blizzId
     *
     * @return integer 
     */
    public function getBlizzId()
    {
        return $this->blizzId;
    }
}
