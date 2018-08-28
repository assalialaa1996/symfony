<?php

namespace ProfilBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;

/**
 * Recomm
 *
 * @ORM\Table(name="recomm")
 * @ORM\Entity(repositoryClass="ProfilBundle\Repository\RecommRepository")
 */
class Recomm
{
    /**
     * @var int
     *@Groups({"all"})
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *@Groups({"all"})
     * @ORM\Column(name="titre", type="string", length=255)
     */
    private $titre;

    /**
     * @var \string
     *
     * *@Groups({"personal","all"})
     * @ORM\Column(name="date", type="string")
     */
    private $date;

    /**
     * @var string
     *@Groups({"all"})
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;


    /**
     * Get id
     *@Groups({"all"})
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set titre
     *@Groups({"all"})
     * @param string $titre
     *
     * @return Recomm
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *@Groups({"all"})
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set date
     *
     * @param \string $date
     *
     * @return Recomm
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *@Groups({"all"})
     * @return \string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Recomm
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *@Groups({"all"})
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
}

