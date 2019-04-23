<?php

namespace App\Entity\Quality;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use DateTime;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Quality\ReactivityQuestionRepository")
 * @ORM\Table(name="reactivity_question")
 */
class ReactivityQuestion 
{
    public function __construct()
    {
        // The askedAt date is when the question is set
        $this->askedAt = new Datetime();
        $this->polestats = new ArrayCollection();
    }

    /** 
     * @var int
     * 
     * @ORM\Id 
     * @ORM\Column(type="integer") 
     * @ORM\GeneratedValue(strategy="AUTO") 
     */
    protected $id;

    /** 
     * @var string
     * 
     * @ORM\Column(type="text", nullable=false)  
     */
    protected $text;

    /**
     * @var \DateTime
     * 
     * @ORM\Column(type="datetime", nullable=false) 
     */
    protected $askedAt;

    /**
     * @ORM\OneToMany(targetEntity="PoleStat", mappedBy="reactivity_question")
     */
    protected $polestats;

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
     * Set text
     *
     * @param string $text
     * @return ReactivityQuestion
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set askedAt
     *
     * @param \DateTime $askedAt
     * @return ReactivityQuestion
     */
    public function setAskedAt($askedAt)
    {
        $this->askedAt = $askedAt;

        return $this;
    }

    /**
     * Get askedAt
     *
     * @return \DateTime
     */
    public function getAskedAt()
    {
        return $this->askedAt;
    }

    /**
     * Check if the reactivity question is over
     *
     * @return bool
     */
    public function isFinished()
    {
        return $this->askedAt->diff(new DateTime(), true)->d >= 1;
    }

    /**
     * Add polestat
     *
     * @param PoleStat $polestat
     * @return ReactivityQuestion
     */
    public function addPolestat(PoleStat $polestat)
    {
        $this->polestats[] = $polestat;

        return $this;
    }

    /**
     * Remove polestats
     *
     * @param PoleStat $polestat
     */
    public function removePolestat( $polestat)
    {
        $this->polestats->removeElement($polestat);
    }

    /**
     * Get polestats
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPolestats()
    {
        return $this->polestats;
    }

    /**
     * Check if polestats is empty
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getHasPolestats()
    {
        return (count($this->polestats) > 0);
    }

    
}