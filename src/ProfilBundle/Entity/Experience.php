<?php

namespace ProfilBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
/**
 * Experience
 *
 * @ORM\Table(name="experience")
 * @ORM\Entity(repositoryClass="ProfilBundle\Repository\ExperienceRepository")
 */
class Experience
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
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *@Groups({"all"})
     * @ORM\Column(name="company", type="string", length=255)
     */
    private $company;

    /**
     * @var \string
     *@Groups({"all"})
     * @ORM\Column(name="start", type="string", nullable=true)
     */
    private $start;

    /**
     * @var \string
     *@Groups({"all"})
     * @ORM\Column(name="end", type="string", nullable=true)
     */
    private $end;

    /**
     * @var string
     *@Groups({"all"})
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;


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
     * Set title.
     *@Groups({"all"})
     * @param string $title
     *
     * @return Experience
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     *@Groups({"all"})
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set company.
     *@Groups({"all"})
     * @param string $company
     *
     * @return Experience
     */
    public function setCompany($company)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company.
     *@Groups({"all"})
     * @return string
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Set start.
     *@Groups({"all"})
     * @param \string $start
     *
     * @return Experience
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
     * @return Experience
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
     * Set description.
     *@Groups({"all"})
     * @param string $description
     *
     * @return Experience
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *@Groups({"all"})
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
}
