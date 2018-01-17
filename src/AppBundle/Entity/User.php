<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table(name="app_users")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @UniqueEntity(fields={"username"}, message="That username is taken!")
 * @UniqueEntity(fields={"email"}, message="It looks like your already have an account!")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64, unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(max=64, maxMessage = "Email must be at most {{ limit }} characters long")
     * @Assert\Email(message = "The email '{{ value }}' is not a valid email.", checkMX = true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=32, unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(min=4, minMessage = "Username must be at least {{ limit }} characters long")
     * @Assert\Length(max=32, maxMessage = "Username must be at most {{ limit }} characters long")
     */
    private $username;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=5, minMessage = "Password must be at least {{ limit }} characters long")
     * @Assert\Length(max=32, minMessage = "Password must be at most {{ limit }} characters long")
     */
    private $plainPassword;

    /**
     * The below length depends on the "algorithm" you use for encoding
     * the password, but this works well with bcrypt.
     *
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     * @ORM\OneToMany(targetEntity="Poll", mappedBy="author")
     * @ORM\OrderBy({"createdAt"="DESC"})
     */
    private $polls;

    public function __toString()
    {
        return $this->username;
    }

    public function __construct()
    {
        $this->isActive = true;
        // may not be needed, see section on salt below
        // $this->salt = md5(uniqid('', true));
        $this->polls = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
        // forces the object to look "dirty" to Doctrine. Avoids
        // Doctrine *not* saving this entity, if only plainPassword changes
        $this->password = null;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    public function getRoles()
    {
        return ['ROLE_USER'];
    }

    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt
            ) = unserialize($serialized);
    }

    /**
     * @Assert\IsTrue(groups={"Registration"}, message = "The password cannot match your username")
     */
    public function isPasswordLegal()
    {
        return $this->username !== $this->password;
    }

    /**
     * @return ArrayCollection|Poll[]
     */
    public function getPolls()
    {
        return $this->polls;
    }

    public function getIsActive()
    {
        return $this->isActive;
    }
}
