<?php
namespace XRealm\AppBundle\Entity;
use XRealm\AppBundle\Entity\Activity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="activity_post")
 * @ORM\Entity()
 */
class ActivityPost extends Activity
{
    /**
     * @ORM\ManyToOne(targetEntity="BlizzCharacter")
     */
    protected $character;
    
    /**
     * @ORM\Column(type="text", length=20512)
     */
    protected $message;


    /**
     * Set message
     *
     * @param string $message
     * @return ActivityPost
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string 
     */
    public function getMessage()
    {
        return $this->message;
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
     * Set postedAt
     *
     * @param \DateTime $postedAt
     * @return ActivityPost
     */
    public function setPostedAt($postedAt)
    {
        $this->postedAt = $postedAt;

        return $this;
    }

    /**
     * Get postedAt
     *
     * @return \DateTime 
     */
    public function getPostedAt()
    {
        return $this->postedAt;
    }

    /**
     * Set isPublic
     *
     * @param boolean $isPublic
     * @return ActivityPost
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
     * Set character
     *
     * @param \XRealm\AppBundle\Entity\BlizzCharacter $character
     * @return ActivityNotification
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

    /**
     * Set group
     *
     * @param \XRealm\AppBundle\Entity\Group $group
     * @return ActivityPost
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


    
    public function getType()
    {
        return 'post';
    }
}
