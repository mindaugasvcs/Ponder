<?php

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="poll_option_vote")
 */
class PollOptionVote
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="PollOption", inversedBy="votes")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $pollOption;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private $voter;

    /**
     * @ORM\Column(type="string")
     */
    private $voterIp;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getPollOption()
    {
        return $this->pollOption;
    }

    public function setPollOption(PollOption $pollOption)
    {
        $this->pollOption = $pollOption;
    }

    public function getVoter()
    {
        return $this->voter;
    }

    public function setVoter(User $voter = null)
    {
        $this->voter = $voter;
    }

    public function getVoterIp()
    {
        return $this->voterIp;
    }

    public function setVoterIp($voterIp)
    {
        $this->voterIp = $voterIp;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }
}