<?php
// src/XRealm/AppBundle/Entity/User.php
namespace XRealm\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * XRealm\AppBundle\Entity\Group
 *
 * @ORM\Table(name="group_character")
 * @ORM\Entity(repositoryClass="XRealm\AppBundle\Entity\Repository\GroupInvolvedRepository")
 */
class GroupInvolvedCharacter
{
    
    const RANK_MEMBER = 1;
    const RANK_ASSIST = 2;
    const RANK_LEADER = 3;
    
    const STATUS_INVITED = 1;
    const STATUS_JOINED = 2;
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Group", inversedBy="involvedCharacters")
     */
    private $group;
    
    /**
     * @ORM\ManyToOne(targetEntity="BlizzCharacter", inversedBy="groups")
     */
    private $character;
    
    /**
     * @ORM\Column(name="status", type="integer", length=4)
     */
    private $status;
    
    /**
     * @ORM\Column(name="rank", type="integer", length=4)
     */
    private $rank;


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
     * @return GroupInvolvedCharacter
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
     * Set rank
     *
     * @param integer $rank
     * @return GroupInvolvedCharacter
     */
    public function setRank($rank)
    {
        $this->rank = $rank;

        return $this;
    }

    /**
     * Get rank
     *
     * @return integer 
     */
    public function getRank()
    {
        return $this->rank;
    }

    /**
     * Set group
     *
     * @param \XRealm\AppBundle\Entity\Group $group
     * @return GroupInvolvedCharacter
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
     * Set character
     *
     * @param \XRealm\AppBundle\Entity\BlizzCharacter $character
     * @return GroupInvolvedCharacter
     */
    public function setCharacter(\XRealm\AppBundle\Entity\BlizzCharacter $character = null)
    {
        $this->character = $character;

        return $this;
    }

    /**
     * Get character
     *
     * @return \XRealm\AppBundle\Entity\BlizzCharacter 
     */
    public function getCharacter()
    {
        return $this->character;
    }
}
