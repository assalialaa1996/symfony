<?php

namespace ProfilBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;use JMS\Serializer\Annotation\Groups;
/**
 * Competence
 *
 * @ORM\Table(name="competence")
 * @ORM\Entity(repositoryClass="ProfilBundle\Repository\CompetenceRepository")
 * @Serializer\ExclusionPolicy("ALL")
 */
class Competence
{
    /**
     * @var int
     *@Groups({"all"})
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *  @Serializer\Expose
     */
    private $id;

    /**
     * @var string
     *@Groups({"all"})
     * @ORM\Column(name="libelle", type="string", length=255)
     * @Serializer\Expose
     */
    private $libelle;


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
     * Set libelle
     *@Groups({"all"})
     * @param string $libelle
     *
     * @return Competence
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *@Groups({"all"})
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }


    /**
     * @var
      *@Groups({"all"})
     * @ORM\OneToMany(targetEntity="niveau" , mappedBy="competences")
     */

    private $niveaus;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->niveaus = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add niveau
     *
     * @param \ProfilBundle\Entity\niveau $niveau
     *
     * @return Competence
     */
    public function addNiveau(\ProfilBundle\Entity\niveau $niveau)
    {
        $this->niveau[] = $niveau;

        return $this;
    }

    /**
     * Remove niveau
     *
     * @param \ProfilBundle\Entity\niveau $niveau
     */
    public function removeNiveau(\ProfilBundle\Entity\niveau $niveau)
    {
        $this->niveaus->removeElement($niveau);
    }

    /**
     * Get niveau
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNiveau()
    {
        return $this->niveaus;
    }
}
