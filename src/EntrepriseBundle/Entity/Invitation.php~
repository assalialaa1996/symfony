<?php

namespace EntrepriseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Invitation
 *
 * @ORM\Table(name="invitation")
 * @ORM\Entity(repositoryClass="EntrepriseBundle\Repository\InvitationRepository")
 */
class Invitation
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;




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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Invitation
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }



    /**
     * @ORM\ManyToOne(targetEntity="EntrepriseBundle\Entity\Compte", inversedBy="invitations")
     * @ORM\JoinColumn(name="comp_id", referencedColumnName="id")
     *
     */
    private $grand_comptes;
    /**
     * @ORM\ManyToOne(targetEntity="ProfilBundle\Entity\Prof" , inversedBy="invitations")
     * @ORM\JoinColumn(name="prof_id", referencedColumnName="id")
     */
    private $profils;


    /**
     * Set grandComptes
     *
     * @param \EntrepriseBundle\Entity\Compte $grandComptes
     *
     * @return Invitation
     */
    public function setGrandComptes(\EntrepriseBundle\Entity\Compte $grandComptes = null)
    {
        $this->grand_comptes = $grandComptes;

        return $this;
    }

    /**
     * Get grandComptes
     *
     * @return \EntrepriseBundle\Entity\Compte
     */
    public function getGrandComptes()
    {
        return $this->grand_comptes;
    }

    /**
     * Set profils
     *
     * @param \ProfilBundle\Entity\Prof $profils
     *
     * @return Invitation
     */
    public function setProfils(\ProfilBundle\Entity\Prof $profils = null)
    {
        $this->profils = $profils;

        return $this;
    }

    /**
     * Get profils
     *
     * @return \ProfilBundle\Entity\Prof
     */
    public function getProfils()
    {
        return $this->profils;
    }
}
