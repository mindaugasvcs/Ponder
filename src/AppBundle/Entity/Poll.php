<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PollRepository")
 * @ORM\Table(name="poll")
 */
class Poll
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $title;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="integer")
     */
    private $hitsCount;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="polls")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $author;

    /**
     * @ORM\OneToMany(targetEntity="PollOption", mappedBy="poll", cascade={"persist"})
     * @ORM\OrderBy({"sequence"="ASC"})
     */
    private $pollOptions;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->hitsCount = 0;
        $this->pollOptions = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getHitsCount()
    {
        return $this->hitsCount;
    }

    public function setHitsCount($hitsCount)
    {
        $this->hitsCount = $hitsCount;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function setAuthor(User $author)
    {
        $this->author = $author;
    }

    /**
     * @return ArrayCollection|PollOption[]
     */
    public function getPollOptions()
    {
        return $this->pollOptions;
    }
}
