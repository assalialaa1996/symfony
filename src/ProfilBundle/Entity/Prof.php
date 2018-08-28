<?php

namespace ProfilBundle\Entity;


use AppBundle\AppBundle;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToOne;
use AppBundle\Entity\User;
use Doctrine\ORM\Mapping\ManyToOne;
use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping\OneToMany;
/**
 * Prof
 *
 *
 * @ORM\Table(name="prof")
 *
 * @ORM\Entity(repositoryClass="ProfilBundle\Repository\ProfRepository")
 *@Serializer\ExclusionPolicy("ALL")


 *
 */
class Prof
{
    /**
     * @var int
     *@Groups({"all"})
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *@Serializer\Expose
     *
     */

    private $id;

    /**
     *  @Groups({"personal"})
     *
     * @Serializer\Expose
     * One prof has One user.
     * @OneToOne(targetEntity="AppBundle\Entity\User", inversedBy="prof",cascade={"persist", "remove"})
     * @JoinColumn(name="user_id", referencedColumnName="id",onDelete="cascade")
     */
    private $user;

    /**
     * @var string
     *@Serializer\Expose
     * @ORM\Column(name="nom", type="string", length=255 , nullable=true)
     *
     */
    private $nom;
    /**
     * @var string
     *@Serializer\Expose
     * @ORM\Column(name="pays", type="string", length=255 , nullable=true)
     *
     */
    private $pays;
    /**
     * @var string
     *@Serializer\Expose
     * @ORM\Column(name="adresse", type="string", length=255 , nullable=true)
     *
     */
    private $adresse;
    /**
     * @var string
     *@Serializer\Expose

     * @ORM\Column(name="prenom", type="string", length=255, nullable=true)
     */
    private $prenom;
    /**
     * @var string
     *
     *@Serializer\Expose
     * @ORM\Column(name="sexe", type="string", length=10, nullable=true)
     */
    private $sexe;

    /**
     * @var string
     * @Groups({"personal"}) *
     *@Serializer\Expose
     *
     * @ORM\Column(name="num_tel", type="string", nullable=true)
     */
    private $numTel;

    /**
     * @var int
     * @Groups({"personal"}) *
     *@Serializer\Expose
     *
     * @ORM\Column(name="sal_min", type="integer", nullable=true)
     */
    private $sal_min;

    /**
     * @var
     * @Groups({"personal"})
     *@Serializer\Expose
     * @ORM\Column(name="dat_naiss", type="string", nullable=true)
     */
    private $datNaiss;

    /**
     * @var bool
     * @Groups({"personal"}) *
     *@Serializer\Expose
     * @ORM\Column(name="verif", type="boolean", nullable=true)
     */
    private $verif;


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
     * @return Prof
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
     * Set prenom
     *
     * @param string $prenom
     *
     * @return Prof
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set numTel
     *
     * @param integer $numTel
     *
     * @return Prof
     */
    public function setNumTel($numTel)
    {
        $this->numTel = $numTel;

        return $this;
    }

    /**
     * Get numTel
     *
     * @return int
     */
    public function getNumTel()
    {
        return $this->numTel;
    }

    /**
     * Set datNaiss
     *
     * @param \string $datNaiss
     *
     * @return Prof
     */
    public function setDatNaiss($datNaiss)
    {
        $this->datNaiss = $datNaiss;

        return $this;
    }

    /**
     * Get datNaiss
     *
     * @return \string
     */
    public function getDatNaiss()
    {
        return $this->datNaiss;
    }

    /**
     * Set verif
     *
     * @param boolean $verif
     *
     * @return Prof
     */
    public function setVerif($verif)
    {
        $this->verif = $verif;

        return $this;
    }

    /**
     * Get verif
     *
     * @return bool
     */

    public function getVerif()
    {
        return $this->verif;
    }

    /**
     * @var
     * @Serializer\Expose
     *@Groups({"all"})
     *      * Many profiles have Many Recommendation.
     * @ManyToMany(targetEntity="Recomm",cascade={"persist"})
     * @JoinTable(name="profil_Recomm",
     *      joinColumns={@JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="Recomm_id", referencedColumnName="id")}
     *      )
     */
    private $recomm;

    /**
     * *@Groups({"all"})
     * @var
     * @Serializer\Expose

     *      * Many profiles have Many Recommendation.
     * @ManyToMany(targetEntity="Contrat",cascade={"persist"})
     * @JoinTable(name="profil_contrat",
     *      joinColumns={@JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="Contrat_id", referencedColumnName="id")}
     *      )
     */
    private $list_contrats;



    /**
     * @var
     * *@Groups({"all"})
     *  Many profiles have Many Experiences.
     * @ManyToMany(targetEntity="Experience",cascade={"persist"})
     * @JoinTable(name="profil_exerience",
     *      joinColumns={@JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="experience_id", referencedColumnName="id")}
     *      )
     *
     * @Serializer\Expose
     */
    private $experiences;



    /**
     * *@Groups({"all"})
     * @var
     *  Many profiles have Many Experiences.
     * @ManyToMany(targetEntity="Education",cascade={"persist"})
     * @JoinTable(name="profil_education",
     *      joinColumns={@JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="education_id", referencedColumnName="id")}
     *      )
     *
     * @Serializer\Expose
     */
    private $educations;
    /**
     * @Groups({"all"})
     * @var
     *
     * @ORM\OneToMany(targetEntity="niveau" , mappedBy="profils")
     * @Serializer\Expose
     */

private $list_comps;


    /**
     * @var
     * *@Groups({"personal"})
     * @ORM\OneToMany(targetEntity="EntrepriseBundle\Entity\Invitation" , mappedBy="profils")
     */

    private $invitations;



    /**
     * Many Features have One Product.
     * @Serializer\Expose
     * @Groups({"personal"})
     * @ManyToOne(targetEntity="AppBundle\Entity\File", inversedBy="profil")
     * @JoinColumn(name="image_id", referencedColumnName="id")
     */

    private $image;
    /**
     * @Groups({"all"})
     * One Product has Many Features.
       *@Serializer\Expose
     * @OneToMany(targetEntity="Langue", mappedBy="profile")
     */

    private $profil;
    /**
     * @var string
     * @Groups({"personal"}) *
     *@Serializer\Expose
     * @ORM\Column(name="description", type="string", nullable=true)
     */
    private $description;
    //
    /**
     * @return mixed
     */
    /**
     * *@Groups({"all"})
     * Constructor
     */
    public function __construct()
    {
        $this->recomms = new \Doctrine\Common\Collections\ArrayCollection();
        $this->list_contrats = new \Doctrine\Common\Collections\ArrayCollection();
        $this->list_comps = new \Doctrine\Common\Collections\ArrayCollection();
        $this->experiences = new \Doctrine\Common\Collections\ArrayCollection();
    }
    /**
     * Set description
     *
     * @param string $description
     *
     * @return Prof
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Prof
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add recomm
     *
     * @param \ProfilBundle\Entity\Recomm $recomm
     *
     * @return Prof
     */
    public function addRecomm(\ProfilBundle\Entity\Recomm $recomm)
    {
        $this->recomm[] = $recomm;

        return $this;
    }

    /**
     * Remove recomm
     *
     * @param \ProfilBundle\Entity\Recomm $recomm
     */
    public function removeRecomm(\ProfilBundle\Entity\Recomm $recomm)
    {
        $this->recomm->removeElement($recomm);
    }

    /**
     * Get recomm
     * @Groups({"all"})
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRecomm()
    {
        return $this->recomm;
    }




    /**
     * Add listComp
     *
     * @param \ProfilBundle\Entity\niveau $listComp
     *
     * @return Prof
     */
    public function addListComp(\ProfilBundle\Entity\niveau $listComp)
    {
        $this->list_comps[] = $listComp;

        return $this;
    }

    /**
     * Remove listComp
     *
     * @param \ProfilBundle\Entity\niveau $listComp
     */
    public function removeListComp(\ProfilBundle\Entity\niveau $listComp)
    {
        $this->list_comps->removeElement($listComp);
    }

    /**
     * Get listComp
     *@Groups({"all"})
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getListComp()
    {
        return $this->list_comps;
    }






    /**
     * Get listComps
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getListComps()
    {
        return $this->list_comps;
    }

    /**
     * Add invitation
     *
     * @param \EntrepriseBundle\Entity\Invitation $invitation
     *
     * @return Prof
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
     * Add experience.
     *@Groups({"all"})
     * @param \ProfilBundle\Entity\Experience $experience
     *
     * @return Prof
     */
    public function addExperience(\ProfilBundle\Entity\Experience $experience)
    {
        $this->experiences[] = $experience;

        return $this;
    }

    /**
     * Remove experience.
     *@Groups({"all"})
     * @param \ProfilBundle\Entity\Experience $experience
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeExperience(\ProfilBundle\Entity\Experience $experience)
    {
        return $this->experiences->removeElement($experience);
    }

    /**
     * Get experiences.
     *@Groups({"all"})
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getExperiences()
    {
        return $this->experiences;
    }

    /**
     * Add education.
     *
     * @param \ProfilBundle\Entity\Education $education
     *
     * @return Prof
     */
    public function addEducation(\ProfilBundle\Entity\Education $education)
    {
        $this->educations[] = $education;

        return $this;
    }

    /**
     * Remove education.
     *
     * @param \ProfilBundle\Entity\Education $education
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeEducation(\ProfilBundle\Entity\Education $education)
    {
        return $this->educations->removeElement($education);
    }

    /**
     * Get educations.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEducations()
    {
        return $this->educations;
    }

    /**
     * Set sexe.
     *
     * @param string|null $sexe
     *
     * @return Prof
     */
    public function setSexe($sexe = null)
    {
        $this->sexe = $sexe;

        return $this;
    }

    /**
     * Get sexe.
     *
     * @return string|null
     */
    public function getSexe()
    {
        return $this->sexe;
    }



    /**
     * Set image.
     *
     * @param \AppBundle\Entity\File|null $image
     *
     * @return Prof
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
     * Add listContrat.
     *
     * @param \ProfilBundle\Entity\Contrat $listContrat
     *
     * @return Prof
     */
    public function addListContrat(\ProfilBundle\Entity\Contrat $listContrat)
    {
        $this->list_contrats[] = $listContrat;

        return $this;
    }

    /**
     * Remove listContrat.
     *
     * @param \ProfilBundle\Entity\Contrat $listContrat
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeListContrat(\ProfilBundle\Entity\Contrat $listContrat)
    {
        return $this->list_contrats->removeElement($listContrat);
    }

    /**
     * Get listContrats.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getListContrats()
    {
        return $this->list_contrats;
    }


    /**
     * Set pays.
     *
     * @param string|null $pays
     *
     * @return Prof
     */
    public function setPays($pays = null)
    {
        $this->pays = $pays;

        return $this;
    }

    /**
     * Get pays.
     *
     * @return string|null
     */
    public function getPays()
    {
        return $this->pays;
    }

    /**
     * Set adresse.
     *
     * @param string|null $adresse
     *
     * @return Prof
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
     * Set salMin.
     *
     * @param int|null $salMin
     *
     * @return Prof
     */
    public function setSalMin($salMin = null)
    {
        $this->sal_min = $salMin;

        return $this;
    }

    /**
     * Get salMin.
     *
     * @return int|null
     */
    public function getSalMin()
    {
        return $this->sal_min;
    }



    /**
     * Add profil.
     *
     * @param \ProfilBundle\Entity\Langue $profil
     *
     * @return Prof
     */
    public function addProfil(\ProfilBundle\Entity\Langue $profil)
    {
        $this->profil[] = $profil;

        return $this;
    }

    /**
     * Remove profil.
     *
     * @param \ProfilBundle\Entity\Langue $profil
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeProfil(\ProfilBundle\Entity\Langue $profil)
    {
        return $this->profil->removeElement($profil);
    }

    /**
     * Get profil.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProfil()
    {
        return $this->profil;
    }
}
