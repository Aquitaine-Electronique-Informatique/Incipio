<?php

namespace App\Entity\Quality;

use Doctrine\ORM\Mapping as ORM;
use Mgate\PersonneBundle\Entity\Membre;
use DateTime;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Quality\MoodAnswerRepository")
 * @ORM\Table(name="mood_answer")
 */
class MoodAnswer{

    public function __construct()
    {
        $this->isValidated = false;
        $this->answeredAt = new DateTime();
    }

    /** 
     * @var int
     * 
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO") 
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="MoodQuestion")
     * @ORM\JoinColumn(name="mood_question_id", referencedColumnName="id")
     */
    protected $mood_question;

    /**
     * @ORM\ManyToOne(targetEntity="Mgate\PersonneBundle\Entity\Membre")
     * @ORM\JoinColumn(name="member_id", referencedColumnName="id")
     */
    protected $member;

    /**
     * @var int
     * 
     * @ORM\Column(type="integer", nullable=false) 
     */
    protected $answer1;

    /**
     * @var array
     * 
     * @ORM\Column(type="array", nullable=false) 
     */
    protected $answer2;

    /**
     * @var string
     * 
     * @ORM\Column(type="text", nullable=true) 
     */
    protected $answer3;

    /**
     * @var \DateTime
     * 
     * @ORM\Column(type="datetime", nullable=true) 
     * 
     */
    protected $answeredAt;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set mood_question
     *
     * @param MoodQuestion $mood_question
     * @return MoodAnswer
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
        return $this->mood_question;
    }

    /**
     * Set member
     *
     * @param Membre $member
     * @return MoodAnswer
     */
    public function setMember(Membre $member)
    {
        $this->member = $member;

        return $this;
    }

    /**
     * Get member
     *
     * @return Membre
     */
    public function getMember()
    {
        return $this->member;
    }

    /**
     * Set answer1
     *
     * @param int $answer
     * @return MoodAnswer
     */
    public function setAnswer1($answer)
    {
        $this->answer1 = $answer;

        return $this;
    }

    /**
     * Get answer1
     *
     * @return int
     */
    public function getAnswer1()
    {
        return $this->answer1;
    }

    /**
     * Set answer2
     *
     * @param array $answer
     * @return MoodAnswer
     */
    public function setAnswer2($answer)
    {
        $this->answer2 = $answer;

        return $this;
    }

    /**
     * Get answer2
     *
     * @return array
     */
    public function getAnswer2()
    {
        return $this->answer2;
    }

    /**
     * Set answer3
     *
     * @param int $answer
     * @return MoodAnswer
     */
    public function setAnswer3($answer)
    {
        $this->answer3 = $answer;

        return $this;
    }

    /**
     * Get answer3
     *
     * @return int
     */
    public function getAnswer3()
    {
        return $this->answer3;
    }

    /**
     * Set answeredAt
     *
     * @param datetime $answeredAt
     * @return MoodAnswer
     */
    public function setAnsweredAt($answeredAt)
    {
        $this->answeredAt = $answeredAt;

        return $this;
    }

    /**
     * Get answeredAt
     *
     * @return datetime
     */
    public function getAnsweredAt()
    {
        return $this->answeredAt;
    }
    
}