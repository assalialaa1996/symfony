<?php

namespace ProfilBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToOne;
use AppBundle\Entity\User;
use Doctrine\ORM\Mapping\ManyToOne;
use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Annotation\Groups;

/**
 * Langue
 *
 * @ORM\Table(name="langue")
 * @ORM\Entity(repositoryClass="ProfilBundle\Repository\LangueRepository")
 */
class Langue
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
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *@Groups({"all"})
     * @ORM\Column(name="niveau", type="string", length=255)
     */
    private $niveau;


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
     * Set nom.
     *
     * @param string $nom
     *
     * @return Langue
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom.
     *@Groups({"all"})
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set niveau.
     *
     * @param string $niveau
     *
     * @return Langue
     */
    public function setNiveau($niveau)
    {
        $this->niveau = $niveau;

        return $this;
    }

    /**
     * Get niveau.
     *@Groups({"all"})
     * @return string
     */
    public function getNiveau()
    {
        return $this->niveau;
    }

    /**
     * Many Features have One Product
     * @ManyToOne(targetEntity="Prof", inversedBy="profil")
     * @JoinColumn(name="profile_id", referencedColumnName="id")
     */

    private $profile;

    /**
     * Set profile.
     *
     * @param \ProfilBundle\Entity\Prof|null $profile
     *
     * @return Langue
     */
    public function setProfile(\ProfilBundle\Entity\Prof $profile = null)
    {
        $this->profile = $profile;

        return $this;
    }

    /**
     * Get profile.
     *
     * @return \ProfilBundle\Entity\Prof|null
     */
    public function getProfile()
    {
        return $this->profile;
    }
}
