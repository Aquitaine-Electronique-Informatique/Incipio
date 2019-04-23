<?php

namespace App\Entity\Quality;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Mgate\PersonneBundle\Entity\Pole;

/**
 * @ORM\Entity
 * @ORM\Table(name="moodstat")
 */
class MoodStat 
{
    public function __construct()
    {

    }

    /** 
     * @var int
     * 
     * @ORM\Id 
     * @ORM\Column(type="integer") 
     * @ORM\GeneratedValue 
     */
    protected $id;

    /**
     * @var int
     * 
     * @Assert\LessThanOrEqual(32767)
     *
     * @ORM\Column(name="promotion", type="smallint", nullable=true)
     */
    protected $promotion;

    /** 
     * @var int
     * 
     * @ORM\Column(type="smallint", nullable=false) 
     */
    protected $month;

    /**
     * @ORM\ManyToOne(targetEntity="MoodQuestion", inversedBy="moodstats")
     * @ORM\JoinColumn(name="mood_question_id", referencedColumnName="id")
     */
    protected $mood_question;

    /**
     * @ORM\ManyToOne(targetEntity="Mgate\PersonneBundle\Entity\Pole")
     * @ORM\JoinColumn(name="pole_id", referencedColumnName="id")
     */
    protected $pole;

    /** 
     * @var int
     * 
     * @ORM\Column(type="smallint", nullable=false) 
     */
    protected $no_answer;

    /** 
     * @var int
     * 
     * @ORM\Column(type="smallint", nullable=false) 
     */
    protected $answer;

    /** 
     * @var int
     * 
     * @ORM\Column(type="smallint", nullable=false) 
     */
    protected $medium_mood;

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
     * Set promotion
     *
     * @param int $promotion
     * @return MoodStat
     */
    public function setPromotion($promotion)
    {
        $this->promotion = $promotion;

        return $this;
    }

    /**
     * Get promotion
     *
     * @return int
     */
    public function getPromotion()
    {
        return $this->promotion;
    }

    /**
     * Set month
     *
     * @param smallint $month
     * @return MoodStat
     */
    public function setMonth($month)
    {
        $this->month = $month;

        return $this;
    }

    /**
     * Get month
     *
     * @return smallint
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * Set mood_question
     *
     * @param MoodQuestion $mood_question
     * @return MoodStat
     */
    public function setMoodQuestion(MoodQuestion $mood_question)
    {
        $this->mood_question = $mood_question;

        return $this;
    }

    /**
     * Get mood_question
     *
     * @return MoodQuestion
     */
    public function getMoodQuestion()
    {
        return $this->Mood_question;
    }

    /**
     * Set pole
     *
     * @param Pole $pole
     * @return Pole
     */
    public function setPole(Pole $pole)
    {
        $this->pole = $pole;

        return $this;
    }

    /**
     * Get pole
     *
     * @return Pole
     */
    public function getPole()
    {
        return $this->pole;
    }

    /**
     * Set no_answer
     *
     * @param smallint $no_answer
     * @return MoodStat
     */
    public function setNoAnswer($no_answer)
    {
        $this->no_answer = $no_answer;

        return $this;
    }

    /**
     * Get no_answer
     *
     * @return smallint
     */
    public function getNoAnswer()
    {
        return $this->no_answer;
    }

    /**
     * Set answer
     *
     * @param smallint $answer
     * @return MoodStat
     */
    public function setAnswer($answer)
    {
        $this->answer = $answer;

        return $this;
    }

    /**
     * Get answer
     *
     * @return smallint
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * Set medium_mood
     *
     * @param smallint $medium_mood
     * @return MoodStat
     */
    public function setMediumMood($medium_mood)
    {
        $this->medium_mood = $medium_mood;

        return $this;
    }

    /**
     * Get medium_mood
     *
     * @return smallint
     */
    public function getMediumMood()
    {
        return $this->medium_mood;
    }
}