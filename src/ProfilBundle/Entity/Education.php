<?php

namespace ProfilBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Annotation\Groups;
/**
 * Education
 *
 * @ORM\Table(name="education")
 * @ORM\Entity(repositoryClass="ProfilBundle\Repository\EducationRepository")
 */
class Education
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
     * @ORM\Column(name="place", type="string", length=255 , nullable=true)
     */
    private $place;


    /**
     * @var string
     *@Groups({"all"})
     * @ORM\Column(name="university", type="string", length=255, nullable=true)
     */
    private $university;

    /**
     * @var string
     *@Groups({"all"})
     * @ORM\Column(name="degree", type="string", length=255, nullable=true)
     */
    private $degree;

    /**
     * @var \string
     *@Groups({"all"})
     * @ORM\Column(name="start", type="string", nullable=true)
     */
    private $start;

    /**
     * @var \string
     *@Groups({"all"})
     * @ORM\Column(name="end", type="string" , nullable=true)
     */
    private $end;


    /**
     * Get id.
     *@Groups({"all"})
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set place.
     *@Groups({"all"})
     * @param string $place
     *
     * @return Education
     */
    public function setPlace($place)
    {
        $this->place = $place;

        return $this;
    }

    /**
     * Get place.
     *@Groups({"all"})
     * @return string
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * Set degree.
     *
     * @param string $degree
     *@Groups({"all"})
     * @return Education
     */
    public function setDegree($degree)
    {
        $this->degree = $degree;

        return $this;
    }

    /**
     * Get degree.
     *@Groups({"all"})
     * @return string
     */
    public function getDegree()
    {
        return $this->degree;
    }

    /**
     * Set start.
     *
     * @param \string $start
     *@Groups({"all"})
     * @return Education
     */
    public function setStart($start)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * Get start.
     *@Groups({"all"})
     * @return \string
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Set end.
     *@Groups({"all"})
     * @param \string $end
     *
     * @return Education
     */
    public function setEnd($end)
    {
        $this->end = $end;

        return $this;
    }

    /**
     * Get end.
     *@Groups({"all"})
     * @return \string
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * Set university.
     *
     * @param string $university
     *@Groups({"all"})
     * @return Education
     */
    public function setUniversity($university)
    {
        $this->university = $university;

        return $this;
    }

    /**
     * Get university.
     *@Groups({"all"})
     * @return string
     */
    public function getUniversity()
    {
        return $this->university;
    }
}
