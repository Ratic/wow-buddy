<?php
// src/XRealm/AppBundle/Entity/User.php
namespace XRealm\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * XRealm\AppBundle\Entity\Group
 *
 * @ORM\Table(name="boss")
 * @ORM\Entity()
 */
class Boss
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
    private $thumbnail;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\ManyToOne(targetEntity="Raid", inversedBy="bosses")
     */
    private $raid;

    /**
     * @ORM\Column(type="integer", length=10)
     */
    private $order;

    /**
     * @ORM\Column(name="blizz_id", type="integer", length=125)
     */
    private $blizzId;

    /**
     * @ORM\Column(type="integer", length=10)
     */
    private $difficulty;

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
     * @return Boss
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
     * @return Boss
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
     * Set slug
     *
     * @param string $slug
     * @return Boss
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
     * Set raid
     *
     * @param \XRealm\AppBundle\Entity\Raid $raid
     * @return Boss
     */
    public function setRaid(\XRealm\AppBundle\Entity\Raid $raid = null)
    {
        $this->raid = $raid;

        return $this;
    }

    /**
     * Get raid
     *
     * @return \XRealm\AppBundle\Entity\Raid 
     */
    public function getRaid()
    {
        return $this->raid;
    }

    /**
     * Set order
     *
     * @param integer $order
     * @return Boss
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return integer 
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set blizzId
     *
     * @param integer $blizzId
     * @return Boss
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

    /**
     * Set difficulty
     *
     * @param integer $difficulty
     * @return Boss
     */
    public function setDifficulty($difficulty)
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    /**
     * Get difficulty
     *
     * @return integer 
     */
    public function getDifficulty()
    {
        return $this->difficulty;
    }
}
