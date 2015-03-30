<?php
// src/XRealm/UserBundle/Entity/User.php
namespace XRealm\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * XRealm\UserBundle\Entity\User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="XRealm\UserBundle\Entity\Repository\UserRepository")
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


    public function __construct()
    {
        $this->roles = new ArrayCollection();
		$this->isActive = true;
		$this->salt = md5(uniqid(null, true));
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
     * @param \XRealm\UserBundle\Entity\Role $roles
     * @return User
     */
    public function addRole(\XRealm\UserBundle\Entity\Role $roles)
    {
        $this->roles[] = $roles;

        return $this;
    }

    /**
     * Remove roles
     *
     * @param \XRealm\UserBundle\Entity\Role $roles
     */
    public function removeRole(\XRealm\UserBundle\Entity\Role $roles)
    {
        $this->roles->removeElement($roles);
    }
    /**
     * Add blizzCharacters
     *
     * @param \XRealm\UserBundle\Entity\BlizzCharacter $blizzCharacters
     * @return User
     */
    public function addBlizzCharacter(\XRealm\UserBundle\Entity\BlizzCharacter $blizzCharacters)
    {
        $this->blizzCharacters[] = $blizzCharacters;

        return $this;
    }

    /**
     * Remove blizzCharacters
     *
     * @param \XRealm\UserBundle\Entity\BlizzCharacter $blizzCharacters
     */
    public function removeBlizzCharacter(\XRealm\UserBundle\Entity\BlizzCharacter $blizzCharacters)
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
