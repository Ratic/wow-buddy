<?php
namespace XRealm\AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Table(name="comment")
 * @ORM\Entity()
 */
class Comment
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\Column(type="text", length=140)
     */
    protected $message;

    /**
     * @ORM\ManyToOne(targetEntity="Activity", inversedBy="comments")
     */
    protected $activity;

    /**
     * @ORM\ManyToOne(targetEntity="BlizzCharacter")
     */
    protected $character;

    /**
     * @ORM\Column(name="posted_at", type="datetime")
     */
    protected $postedAt;

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
     * Set message
     *
     * @param string $message
     * @return Comment
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
     * Set postedAt
     *
     * @param \DateTime $postedAt
     * @return Comment
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
     * Set activity
     *
     * @param \XRealm\AppBundle\Entity\Activity $activity
     * @return Comment
     */
    public function setActivity(\XRealm\AppBundle\Entity\Activity $activity = null)
    {
        $this->activity = $activity;

        return $this;
    }

    /**
     * Get activity
     *
     * @return \XRealm\AppBundle\Entity\Activity 
     */
    public function getActivity()
    {
        return $this->activity;
    }

    /**
     * Set character
     *
     * @param \XRealm\AppBundle\Entity\BlizzCharacter $character
     * @return Comment
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
