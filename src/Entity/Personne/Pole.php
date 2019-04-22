<?php

namespace App\Entity\Personne;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Mgate\PersonneBundle\Entity\Pole.
 *
 * @ORM\Table()
 * @ORM\Entity
 * @UniqueEntity("nom")
 */
class Pole
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="nom", type="string", length=127)
     */
    private $nom;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get nom.
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set nom.
     *
     * @param string $nom
     *
     * @return Pole
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

}

