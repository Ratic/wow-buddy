<?php
namespace XRealm\AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Table(name="activity")
 * @ORM\Entity(repositoryClass="XRealm\AppBundle\Entity\Repository\ActivityRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string") 
 * @ORM\DiscriminatorMap({"activity_notification" = "ActivityNotification", "activity_post" = "ActivityPost"})
 */
class Activity
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(name="posted_at", type="datetime")
     */
    protected $postedAt;
    
    /**
     * @ORM\ManyToOne(targetEntity="Group", inversedBy="activities")
     */
    protected $group;
    
    /**
     * @ORM\Column(name="is_public", type="boolean")
     */
    protected $isPublic;

    /**
     * @ORM\Column(name="is_sticky", type="boolean")
     */
    protected $isSticky;

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="activity")
     * @ORM\OrderBy({"postedAt" = "ASC"})
     */
    protected $comments;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->comments = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Activity
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
     * @return Activity
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
     * Set group
     *
     * @param \XRealm\AppBundle\Entity\Group $group
     * @return Activity
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
     * Set isSticky
     *
     * @param boolean $isSticky
     * @return ActivityPost
     */
    public function setIsSticky($isSticky)
    {
        $this->isSticky = $isSticky;

        return $this;
    }

    /**
     * Get isSticky
     *
     * @return boolean
     */
    public function getIsSticky()
    {
        return $this->isSticky;
    }

    /**
     * Add comments
     *
     * @param \XRealm\AppBundle\Entity\Comment $comments
     * @return Activity
     */
    public function addComment(\XRealm\AppBundle\Entity\Comment $comments)
    {
        $this->comments[] = $comments;

        return $this;
    }

    /**
     * Remove comments
     *
     * @param \XRealm\AppBundle\Entity\Comment $comments
     */
    public function removeComment(\XRealm\AppBundle\Entity\Comment $comments)
    {
        $this->comments->removeElement($comments);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getComments()
    {
        return $this->comments;
    }
}
