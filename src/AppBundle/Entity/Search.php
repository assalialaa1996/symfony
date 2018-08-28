<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToOne;
use AppBundle\Entity\User;
use Doctrine\ORM\Mapping\ManyToOne;
use JMS\Serializer\Annotation as Serializer;
/**
 * Search
 *
 * @ORM\Table(name="search")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SearchRepository")
 */
class Search
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
     * @var bool
     *
     * @ORM\Column(name="comp", type="boolean")
     */
    private $comp;

    /**
     * @var bool
     *
     * @ORM\Column(name="contrat", type="boolean")
     */
    private $contrat;
    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string")
     */
    private $country;



    /**
     * @var bool
     *
     * @ORM\Column(name="salaire", type="boolean")
     */
    private $salaire;

    /**
     * @var integer
     *
     * @ORM\Column(name="sal", type="integer")
     */
    private $sal;

    /**
     * @var bool
     *
     * @ORM\Column(name="localisation", type="boolean")
     */
    private $localisation;

    /**
     * @var array
     *
     * @ORM\Column(name="list_comp", type="array")
     */
    private $listComp;

    /**
     * @var array
     *
     * @ORM\Column(name="lis_contrat", type="array")
     */
    private $lisContrat;

    /**
     * @var array
     *
     * @ORM\Column(name="language", type="array")
     */
    private $language;

    /**
     * @var boolean
     *
     * @ORM\Column(name="lang", type="boolean")
     */
    private $lang;

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
     * Set comp.
     *
     * @param bool $comp
     *
     * @return Search
     */
    public function setComp($comp)
    {
        $this->comp = $comp;

        return $this;
    }

    /**
     * Get comp.
     *
     * @return bool
     */
    public function getComp()
    {
        return $this->comp;
    }

    /**
     * Set contrat.
     *
     * @param bool $contrat
     *
     * @return Search
     */
    public function setContrat($contrat)
    {
        $this->contrat = $contrat;

        return $this;
    }

    /**
     * Get contrat.
     *
     * @return bool
     */
    public function getContrat()
    {
        return $this->contrat;
    }

    /**
     * Set salaire.
     *
     * @param bool $salaire
     *
     * @return Search
     */
    public function setSalaire($salaire)
    {
        $this->salaire = $salaire;

        return $this;
    }

    /**
     * Get salaire.
     *
     * @return bool
     */
    public function getSalaire()
    {
        return $this->salaire;
    }

    /**
     * Set localisation.
     *
     * @param bool $localisation
     *
     * @return Search
     */
    public function setLocalisation($localisation)
    {
        $this->localisation = $localisation;

        return $this;
    }

    /**
     * Get localisation.
     *
     * @return bool
     */
    public function getLocalisation()
    {
        return $this->localisation;
    }

    /**
     * Set listComp.
     *
     * @param array $listComp
     *
     * @return Search
     */
    public function setListComp($listComp)
    {
        $this->listComp = $listComp;

        return $this;
    }

    /**
     * Get listComp.
     *
     * @return array
     */
    public function getListComp()
    {
        return $this->listComp;
    }

    /**
     * Set lisContrat.
     *
     * @param array $lisContrat
     *
     * @return Search
     */
    public function setLisContrat($lisContrat)
    {
        $this->lisContrat = $lisContrat;

        return $this;
    }

    /**
     * Get lisContrat.
     *
     * @return array
     */
    public function getLisContrat()
    {
        return $this->lisContrat;
    }







    /**
     * Set loc.
     *
     * @param \ProfilBundle\Entity\City|null $loc
     *
     * @return Search
     */
    public function setLoc(\ProfilBundle\Entity\City $loc = null)
    {
        $this->loc = $loc;

        return $this;
    }

    /**
     * Get loc.
     *
     * @return \ProfilBundle\Entity\City|null
     */
    public function getLoc()
    {
        return $this->loc;
    }

    /**
     * Set sal.
     *
     * @param int $sal
     *
     * @return Search
     */
    public function setSal($sal)
    {
        $this->sal = $sal;

        return $this;
    }

    /**
     * Get sal.
     *
     * @return int
     */
    public function getSal()
    {
        return $this->sal;
    }

    /**
     * Set country.
     *
     * @param string $country
     *
     * @return Search
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country.
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set language.
     *
     * @param array $language
     *
     * @return Search
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language.
     *
     * @return array
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set lang.
     *
     * @param bool $lang
     *
     * @return Search
     */
    public function setLang($lang)
    {
        $this->lang = $lang;

        return $this;
    }

    /**
     * Get lang.
     *
     * @return bool
     */
    public function getLang()
    {
        return $this->lang;
    }
}
