<?php

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="poll_option")
 */
class PollOption
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $sequence;

    /**
     * @ORM\Column(type="integer")
     */
    private $votesCount;

    /**
     * @ORM\Column(type="string")
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity="Poll", inversedBy="pollOptions")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $poll;

    /**
     * @ORM\OneToMany(targetEntity="PollOptionVote", mappedBy="pollOption")
     * @ORM\OrderBy({"createdAt"="DESC"})
     */
    private $votes;

    public function __construct()
    {
        $this->votesCount = 0;
        $this->votes = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getSequence()
    {
        return $this->sequence;
    }

    public function setSequence($sequence)
    {
        $this->sequence = $sequence;
    }

    public function getVotesCount()
    {
        return $this->votesCount;
    }

    public function setVotesCount($votesCount)
    {
        $this->votesCount = $votesCount;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function getPoll()
    {
        return $this->poll;
    }

    public function setPoll(Poll $poll)
    {
        $this->poll = $poll;
    }

    public function getVotes()
    {
        return $this->votes;
    }
}
