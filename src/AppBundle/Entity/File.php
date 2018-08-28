<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping\OneToMany;
use JMS\Serializer\Annotation as Serializer;
/**
 * File
 *
 * @ORM\Table(name="file")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FileRepository")
 */
class File
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
     * @Assert\Image()
     * @ORM\Column(name="image", type="string", length=255)
     */
    private $image;
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
     * Set image
     *
     * @param string $image
     *
     * @return File
     */
    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }
    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }
    /**
     * One Product has Many Features.
     * @OneToMany(targetEntity="ProfilBundle\Entity\Prof", mappedBy="image")
     */


    private $profil;
    /**
     * One Product has Many Features.
     * @OneToMany(targetEntity="EntrepriseBundle\Entity\Compte", mappedBy="image")
     */


    private $compte;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->profil = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add profil.
     *
     * @param \ProfilBundle\Entity\Prof $profil
     *
     * @return File
     */
    public function addProfil(\ProfilBundle\Entity\Prof $profil)
    {
        $this->profil[] = $profil;

        return $this;
    }

    /**
     * Remove profil.
     *
     * @param \ProfilBundle\Entity\Prof $profil
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeProfil(\ProfilBundle\Entity\Prof $profil)
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

    /**
     * Add compte.
     *
     * @param \EntrepriseBundle\Entity\Compte $compte
     *
     * @return File
     */
    public function addCompte(\EntrepriseBundle\Entity\Compte $compte)
    {
        $this->compte[] = $compte;

        return $this;
    }

    /**
     * Remove compte.
     *
     * @param \EntrepriseBundle\Entity\Compte $compte
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeCompte(\EntrepriseBundle\Entity\Compte $compte)
    {
        return $this->compte->removeElement($compte);
    }

    /**
     * Get compte.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCompte()
    {
        return $this->compte;
    }
}
