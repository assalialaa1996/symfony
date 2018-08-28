<?php

namespace PaymentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
/**
 * Pack
 *
 * @ORM\Table(name="pack")
 * @ORM\Entity(repositoryClass="PaymentBundle\Repository\PackRepository")
 * @Serializer\ExclusionPolicy("ALL")
 */
class Pack
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Expose
     */
    private $id;

    /**
     * @var string
     *@Serializer\Expose
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;



    /**
     * @var int
     *
     * @ORM\Column(name="credits", type="integer")
     * @Serializer\Expose
     */
    private $credits;
    /**
     * @var int
     *
     * @ORM\Column(name="prix", type="integer")
     * @Serializer\Expose
     */
    private $prix;

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
     * Set nom
     *
     * @param string $nom
     *
     * @return Pack
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }






    /**
     * Constructor
     */
    public function __construct()
    {

    }







    /**
     * Set credits.
     *
     * @param int $credits
     *
     * @return Pack
     */
    public function setCredits($credits)
    {
        $this->credits = $credits;

        return $this;
    }

    /**
     * Get credits.
     *
     * @return int
     */
    public function getCredits()
    {
        return $this->credits;
    }

    /**
     * Set prix.
     *
     * @param int $prix
     *
     * @return Pack
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix.
     *
     * @return int
     */
    public function getPrix()
    {
        return $this->prix;
    }
}
