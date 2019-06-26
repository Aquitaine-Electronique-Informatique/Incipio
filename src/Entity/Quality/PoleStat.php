<?php

namespace App\Entity\Quality;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Personne\Pole;

/**
 * @ORM\Entity
 * @ORM\Table(name="polestat")
 */
class PoleStat 
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
     * @ORM\ManyToOne(targetEntity="ReactivityQuestion", inversedBy="polestats")
     * @ORM\JoinColumn(name="reactivity_question_id", referencedColumnName="id")
     */
    protected $reactivity_question;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Personne\Pole")
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
    protected $good_answer;

    /** 
     * @var int
     * 
     * @ORM\Column(type="smallint", nullable=false) 
     */
    protected $bad_answer;

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
     * @return PoleStat
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
     * @return PoleStat
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
     * Set reactivity_question
     *
     * @param ReactivityQuestion $reactivity_question
     * @return PoleStat
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
     * @return PoleStat
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
     * Set bad_answer
     *
     * @param smallint $bad_answer
     * @return PoleStat
     */
    public function setBadAnswer($bad_answer)
    {
        $this->bad_answer = $bad_answer;

        return $this;
    }

    /**
     * Get bad_answer
     *
     * @return smallint
     */
    public function getBadAnswer()
    {
        return $this->bad_answer;
    }

    /**
     * Set good_answer
     *
     * @param smallint $good_answer
     * @return PoleStat
     */
    public function setGoodAnswer($good_answer)
    {
        $this->good_answer = $good_answer;

        return $this;
    }

    /**
     * Get good_answer
     *
     * @return smallint
     */
    public function getGoodAnswer()
    {
        return $this->good_answer;
    }
}