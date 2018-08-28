<?php

namespace EntrepriseBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\JoinColumn;
use JMS\Serializer\Annotation as Serializer;
use Doctrine\ORM\Mapping\OneToMany;
/**
 * Planing
 *
 * @ORM\Table(name="planing")
 * @ORM\Entity(repositoryClass="EntrepriseBundle\Repository\PlaningRepository")
 */
class Planing
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=255)
     */
    private $titre;
    /**

     * @var
     * @ManyToMany(targetEntity="ProfilBundle\Entity\Prof",cascade={"persist"})
     * @JoinTable(name="planing_profiles",
     *      joinColumns={@JoinColumn(name="planing_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="profile_id", referencedColumnName="id")}
     *      )
     *
     * @Serializer\Expose
     */
    private $profiles;
    /**
     * @var string
     *
     * @ORM\Column(name="date", type="string", length=255)
     */
    private $date;


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
     * Set titre.
     *
     * @param string $titre
     *
     * @return Planing
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre.
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set date.
     *
     * @param string $date
     *
     * @return Planing
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date.
     *
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->profiles = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add profile.
     *
     * @param \ProfilBundle\Entity\Prof $profile
     *
     * @return Planing
     */
    public function addProfile(\ProfilBundle\Entity\Prof $profile)
    {
        $this->profiles[] = $profile;

        return $this;
    }

    /**
     * Remove profile.
     *
     * @param \ProfilBundle\Entity\Prof $profile
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeProfile(\ProfilBundle\Entity\Prof $profile)
    {
        return $this->profiles->removeElement($profile);
    }

    /**
     * Get profiles.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProfiles()
    {
        return $this->profiles;
    }
}
