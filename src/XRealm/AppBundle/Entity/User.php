<?php
// src/XRealm/AppBundle/Entity/User.php
namespace XRealm\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * XRealm\AppBundle\Entity\User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="XRealm\AppBundle\Entity\Repository\UserRepository")
 */
class User implements AdvancedUserInterface, \Serializable
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=60, unique=true, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     * @ORM\ManyToMany(targetEntity="Role", inversedBy="users")
     *
     */
    private $roles;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $salt;

    /**
     * @ORM\OneToMany(targetEntity="BlizzCharacter", mappedBy="user")
     */
    private $blizzCharacters;
    
    /**
     * @ORM\Column(type="string", length=32)
     */
    protected $apiAuth;
    
    /**
     * @ORM\Column(type="datetime")
     */
    protected $authExpiresAt;


    public function __construct()
    {
        $this->roles = new ArrayCollection();
        $this->isActive = true;
        $this->salt = md5(uniqid(null, true));
        $this->apiAuth = md5(uniqid(null, true));
        $now = new \DateTime();
        $this->authExpiresAt = $now->modify('+1day');
    }
    
    public function getAuthExpiresAt()
    {
        return $this->authExpiresAt;
    }

    public function setAuthExpiresAt(\DateTime $at)
    {
        $this->authExpiresAt = $at;
    }
    
    public function getRoles()
    {
        return $this->roles->toArray();
    }

    /**
     * @inheritDoc
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @inheritDoc
     */
    public function getApiAuthToken()
    {
        return $this->apiAuth;
    }
    
    public function setApiAuthToken($token)
    {
        $this->apiAuth = $token;
    }
    /**
     * @inheritDoc
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            $this->salt,
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            $this->salt
        ) = unserialize($serialized);
    }

    /**
     * @inheritDoc
     */
    public function isAccountNonExpired()
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function isAccountNonLocked()
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function isCredentialsNonExpired()
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function isEnabled()
    {
        return $this->isActive;
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
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return User
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
     * Set salt
     *
     * @param string $salt
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Add roles
     *
     * @param \XRealm\AppBundle\Entity\Role $roles
     * @return User
     */
    public function addRole(\XRealm\AppBundle\Entity\Role $roles)
    {
        $this->roles[] = $roles;

        return $this;
    }

    /**
     * Remove roles
     *
     * @param \XRealm\AppBundle\Entity\Role $roles
     */
    public function removeRole(\XRealm\AppBundle\Entity\Role $roles)
    {
        $this->roles->removeElement($roles);
    }
    /**
     * Add blizzCharacters
     *
     * @param \XRealm\AppBundle\Entity\BlizzCharacter $blizzCharacters
     * @return User
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

    /**
     * Set apiAuth
     *
     * @param string $apiAuth
     * @return User
     */
    public function setApiAuth($apiAuth)
    {
        $this->apiAuth = $apiAuth;

        return $this;
    }

    /**
     * Get apiAuth
     *
     * @return string 
     */
    public function getApiAuth()
    {
        return $this->apiAuth;
    }
}
