<?php

namespace EntrepriseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToOne;
use AppBundle\Entity\User;
use Doctrine\ORM\Mapping\ManyToOne;
use JMS\Serializer\Annotation as Serializer;

use Doctrine\ORM\Mapping\OneToMany;

/**
 * Compte
 *
 * @ORM\Table(name="compte")
 * @ORM\Entity(repositoryClass="EntrepriseBundle\Repository\CompteRepository")
 */
class Compte
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

     * @var
     *  Many grand comptes have Many planing.
     * @ManyToMany(targetEntity="Planing",cascade={"persist"})
     * @JoinTable(name="recrut_planing",
     *      joinColumns={@JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="planing_id", referencedColumnName="id")}
     *      )
     *
     * @Serializer\Expose
     */
    private $planings;
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
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=true )
     */
    private $nom;


    /**
     * @var string
     *
     * @ORM\Column(name="secteur", type="string", length=255 , nullable=true)
     */
    private $secteur;
    /**
     * @var string
     *
     * @ORM\Column(name="presentation", type="string", length=255, nullable=true )
     */
    private $presentation;
    /**
 * @var int
 *
 * @ORM\Column(name="num_tel", type="integer", nullable=true)
 */
    private $numTel;
    /**
     * @var int
     *
     * @ORM\Column(name="n_salair", type="integer", nullable=true)
     */
    private $n_salair;
    /**
     * @var int
     *
     * @ORM\Column(name="num_Siret", type="integer", nullable=true)
     */
    private $numSiret;
    /**
     * @var int
     *
     * @ORM\Column(name="solde", type="integer", nullable=true)
     */
    private $solde;
    /**
 * @var int
 *
 * @ORM\Column(name="fax", type="integer", nullable=true)
 */
    private $fax;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255 , nullable=true)
     */

    private $adresse;

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Compte
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
     * Set presentation
     *
     * @param string $presentation
     *
     * @return Compte
     */
    public function setPresentation($presentation)
    {
        $this->presentation = $presentation;

        return $this;
    }

    /**
     * Get presentation
     *
     * @return string
     */
    public function getPresentation()
    {
        return $this->presentation;
    }

    /**
     * Set numTel
     *
     * @param integer $numTel
     *
     * @return Compte
     */
    public function setNumTel($numTel)
    {
        $this->numTel = $numTel;

        return $this;
    }

    /**
     * Get numTel
     *
     * @return integer
     */
    public function getNumTel()
    {
        return $this->numTel;
    }

    /**
     * Set numSiret
     *
     * @param integer $numSiret
     *
     * @return Compte
     */
    public function setNumSiret($numSiret)
    {
        $this->numSiret = $numSiret;

        return $this;
    }

    /**
     * Get numSiret
     *
     * @return integer
     */
    public function getNumSiret()
    {
        return $this->numSiret;
    }

    /**
     * Set solde
     *
     * @param integer $solde
     *
     * @return Compte
     */
    public function setSolde($solde)
    {
        $this->solde = $solde;

        return $this;
    }

    /**
     * Get solde
     *
     * @return integer
     */
    public function getSolde()
    {
        return $this->solde;
    }

    /**
     * Set fax
     *
     * @param integer $fax
     *
     * @return Compte
     */
    public function setFax($fax)
    {
        $this->fax = $fax;

        return $this;
    }

    /**
     * Get fax
     *
     * @return integer
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * Set city
     *
     * @param \ProfilBundle\Entity\City $city
     *
     * @return Compte
     */
    public function setCity(\ProfilBundle\Entity\City $city = null)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return \ProfilBundle\Entity\City
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @var
     *  Many Grand-compte have Many profil-it.
     * @ManyToMany(targetEntity="ProfilBundle\Entity\Prof",cascade={"persist"})
     * @JoinTable(name="compte_profil",
     *      joinColumns={@JoinColumn(name="compte_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="profil_id", referencedColumnName="id")}
     *      )
     */
    private $profils;

    /**
     * @var
     * @ORM\OneToMany(targetEntity="EntrepriseBundle\Entity\Invitation" , mappedBy="grand_comptes")
     */

    private $invitations;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->profils = new \Doctrine\Common\Collections\ArrayCollection();
        $this->invitations = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add profil
     *
     * @param \ProfilBundle\Entity\Prof $profil
     *
     * @return Compte
     */
    public function addProfil(\ProfilBundle\Entity\Prof $profil)
    {
        $this->profils[] = $profil;

        return $this;
    }

    /**
     * Remove profil
     *
     * @param \ProfilBundle\Entity\Prof $profil
     */
    public function removeProfil(\ProfilBundle\Entity\Prof $profil)
    {
        $this->profils->removeElement($profil);
    }

    /**
     * Get profils
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProfils()
    {
        return $this->profils;
    }

    /**
     * Add invitation
     *
     * @param \EntrepriseBundle\Entity\Invitation $invitation
     *
     * @return Compte
     */
    public function addInvitation(\EntrepriseBundle\Entity\Invitation $invitation)
    {
        $this->invitations[] = $invitation;

        return $this;
    }

    /**
     * Remove invitation
     *
     * @param \EntrepriseBundle\Entity\Invitation $invitation
     */
    public function removeInvitation(\EntrepriseBundle\Entity\Invitation $invitation)
    {
        $this->invitations->removeElement($invitation);
    }

    /**
     * Get invitations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getInvitations()
    {
        return $this->invitations;
    }



    /**
     * @var
     * @ORM\ManyToOne(targetEntity="PaymentBundle\Entity\Plan", inversedBy="compte")
     * @ORM\JoinColumn(name="plan_id", referencedColumnName="id")
     *
     */
    private $plans;


 





    /**
     *
     * One prof has One user.
     * @OneToOne(targetEntity="AppBundle\Entity\User", inversedBy="compte",cascade={"persist", "remove"})
     * @JoinColumn(name="user_id", referencedColumnName="id",onDelete="cascade")
     */
    private $user;

    /**
     * Set user.
     *
     * @param \AppBundle\Entity\User|null $user
     *
     * @return Compte
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return \AppBundle\Entity\User|null
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set numSalair.
     *
     * @param int|null $numSalair
     *
     * @return Compte
     */
    public function setNumSalair($numSalair = null)
    {
        $this->num_salair = $numSalair;

        return $this;
    }

    /**
     * Get numSalair.
     *
     * @return int|null
     */
    public function getNumSalair()
    {
        return $this->num_salair;
    }

    /**
     * Set nSalair.
     *
     * @param int|null $nSalair
     *
     * @return Compte
     */
    public function setNSalair($nSalair = null)
    {
        $this->n_salair = $nSalair;

        return $this;
    }

    /**
     * Get nSalair.
     *
     * @return int|null
     */
    public function getNSalair()
    {
        return $this->n_salair;
    }

    /**
     * Set secteur.
     *
     * @param string $secteur
     *
     * @return Compte
     */
    public function setSecteur($secteur)
    {
        $this->secteur = $secteur;

        return $this;
    }

    /**
     * Get secteur.
     *
     * @return string
     */
    public function getSecteur()
    {
        return $this->secteur;
    }
    /**
     * Many Features have One Product.
     * @ManyToOne(targetEntity="AppBundle\Entity\File", inversedBy="compte")
     * @JoinColumn(name="image_id", referencedColumnName="id")
     */

    private $image;

    /**
     * Set image.
     *
     * @param \AppBundle\Entity\File|null $image
     *
     * @return Compte
     */
    public function setImage(\AppBundle\Entity\File $image = null)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image.
     *
     * @return \AppBundle\Entity\File|null
     */
    public function getImage()
    {
        return $this->image;
    }
    /**
     * @var string
     *
     * @ORM\Column(name="stripe_id", type="string", length=255 , nullable=true)
     */
    private $stripe_id;


    /**
     * Set stripeId.
     *
     * @param string $stripeId
     *
     * @return Compte
     */
    public function setStripeId($stripeId)
    {
        $this->stripe_id = $stripeId;

        return $this;
    }

    /**
     * Get stripeId.
     *
     * @return string
     */
    public function getStripeId()
    {
        return $this->stripe_id;
    }

    /**
     * Set adresse.
     *
     * @param string|null $adresse
     *
     * @return Compte
     */
    public function setAdresse($adresse = null)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse.
     *
     * @return string|null
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set plans.
     *
     * @param \PaymentBundle\Entity\Plan|null $plans
     *
     * @return Compte
     */
    public function setPlans(\PaymentBundle\Entity\Plan $plans = null)
    {
        $this->plans = $plans;

        return $this;
    }

    /**
     * Get plans.
     *
     * @return \PaymentBundle\Entity\Plan|null
     */
    public function getPlans()
    {
        return $this->plans;
    }


    /**
     * Add planing.
     *
     * @param \EntrepriseBundle\Entity\Planing $planing
     *
     * @return Compte
     */
    public function addPlaning(\EntrepriseBundle\Entity\Planing $planing)
    {
        $this->planings[] = $planing;

        return $this;
    }

    /**
     * Remove planing.
     *
     * @param \EntrepriseBundle\Entity\Planing $planing
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removePlaning(\EntrepriseBundle\Entity\Planing $planing)
    {
        return $this->planings->removeElement($planing);
    }

    /**
     * Get planings.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPlanings()
    {
        return $this->planings;
    }
}
