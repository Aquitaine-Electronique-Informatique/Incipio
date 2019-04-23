<?php

namespace App\Entity\Quality;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use DateTime;

/**
 * @ORM\Entity
 * @ORM\Table(name="mood_question")
 */
class MoodQuestion{

    public function __construct()
    {
         // The askedAt date is when the question is set
         $this->askedAt = new Datetime();
         $this->moodstats = new ArrayCollection();
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
     * @var \DateTime
     * 
     * @ORM\Column(type="datetime", nullable=false) 
     */
    protected $askedAt;

    /**
     * @ORM\OneToMany(targetEntity="MoodStat", mappedBy="mood_question")
     */
    protected $moodstats;

    /**
     * Set askedAt
     *
     * @param \DateTime $askedAt
     * @return MoodQuestion
     */
    public function setAskedAt($askedAt)
    {
        $this->askedAt = $askedAt;

        return $this;
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
     * Get askedAt
     *
     * @return \DateTime
     */
    public function getAskedAt()
    {
        return $this->askedAt;
    }

    /**
     * Add moodstat
     *
     * @param Moodstat $moodstat
     * @return MoodQuestion
     */
    public function addMoodStat(Moodstat $moodstat)
    {
        $this->moodstats[] = $moodstat;

        return $this;
    }

    /**
     * Remove moodstats
     *
     * @param Moodstat $moodstat
     */
    public function removeMoodStat($moodstat)
    {
        $this->moodstats->removeElement($moodstat);
    }

    /**
     * Get moodstats
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMoodStats()
    {
        return $this->moodstats;
    }

    /**
     * Check if moodstats is empty
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getHasMoodStats()
    {
        return (count($this->moodstats) > 0);
    }

     /**
     * Check if the mood question is over
     *
     * @return bool
     */
    public function isFinished()
    {
        return $this->askedAt->diff(new DateTime(), true)->d >= 1;
    }

}