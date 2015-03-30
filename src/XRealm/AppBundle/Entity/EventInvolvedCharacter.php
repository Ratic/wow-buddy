<?php
// src/XRealm/AppBundle/Entity/User.php
namespace XRealm\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * XRealm\AppBundle\Entity\Group
 *
 * @ORM\Table(name="event_character")
 * @ORM\Entity(repositoryClass="XRealm\AppBundle\Entity\Repository\EventInvolvedRepository")
 */
class EventInvolvedCharacter
{
    const STATUS_ACCEPTED = 1;
    const STATUS_DECLINED = 2;
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Event", inversedBy="involvedCharacters")
     */
    private $event;

    /**
     * @ORM\ManyToOne(targetEntity="BlizzCharacter", inversedBy="events")
     */
    private $character;

    /**
     * @ORM\Column(name="comment", type="text", length=255, nullable=true)
     */
    private $comment;

    /**
     * @ORM\Column(name="status", type="integer", length=3)
     */
    private $status;

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
     * Set comment
     *
     * @param string $comment
     * @return EventInvolvedCharacter
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string 
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set event
     *
     * @param \XRealm\AppBundle\Entity\Event $event
     * @return EventInvolvedCharacter
     */
    public function setEvent(\XRealm\AppBundle\Entity\Event $event = null)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Get event
     *
     * @return \XRealm\AppBundle\Entity\Event 
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Set character
     *
     * @param \XRealm\AppBundle\Entity\BlizzCharacter $character
     * @return EventInvolvedCharacter
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
     * Constructor
     */
    public function __construct()
    {
        $this->involvedCharacters = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return EventInvolvedCharacter
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
}
