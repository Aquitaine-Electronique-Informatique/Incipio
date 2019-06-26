<?php

namespace App\Entity\Quality;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Personne\Membre;
use DateTime;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Quality\ReactivityAnswerRepository")
 * @ORM\Table(name="reactivity_answer")
 */
class ReactivityAnswer{

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
     * @ORM\ManyToOne(targetEntity="App\Entity\Quality\ReactivityQuestion")
     * @ORM\JoinColumn(name="reactivity_question_id", referencedColumnName="id")
     */
    protected $reactivity_question;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Personne\Membre")
     * @ORM\JoinColumn(name="member_id", referencedColumnName="id")
     */
    protected $member;

    /**
     * @var string
     * 
     * @ORM\Column(type="text", nullable=true) 
     */
    protected $answer;

    /**
     * @var \DateTime
     * 
     * @ORM\Column(type="datetime", nullable=true) 
     * 
     */
    protected $answeredAt;

    /**
     * @var bool
     * 
     *  @ORM\Column(type="boolean", nullable=false) 
     */
    protected $isValidated;

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
     * Set reactivity_question
     *
     * @param ReactivityQuestion $reactivity_question
     * @return ReactivityAnswer
     */
    public function setReactivityQuestion(ReactivityQuestion $reactivity_question)
    {
        $this->reactivity_question = $reactivity_question;

        return $this;
    }

    /**
     * Get reactivity_question
     *
     * @return ReactivityQuestion
     */
    public function getReactivityQuestion()
    {
        return $this->reactivity_question;
    }

    /**
     * Set member
     *
     * @param Membre $member
     * @return ReactivityAnswer
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
     * Set answer
     *
     * @param string $answer
     * @return ReactivityAnswer
     */
    public function setAnswer($answer)
    {
        $this->answer = $answer;

        return $this;
    }

    /**
     * Get answer
     *
     * @return string
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * Set answeredAt
     *
     * @param datetime $answeredAt
     * @return ReactivityAnswer
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

    /**
     * Set isValidated
     *
     * @param boolean $isValidated
     * @return ReactivityAnswer
     */
    public function setIsValidated($isValidated)
    {
        $this->isValidated = $isValidated;

        return $this;
    }

    /**
     * Get isValidated
     *
     * @return boolean
     */
    public function getIsValidated()
    {
        return $this->isValidated;
    }
}