<?php

namespace ChatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Message
 *
 * @ORM\Table(name="message")
 * @ORM\Entity(repositoryClass="ChatBundle\Repository\MessageRepository")

 */
class Message
{
    /**
     * @var int

     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
private $id;
    /**
     * @var \DateTime

     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @var string

     * @ORM\Column(name="corps", type="string", length=255)
     */
    private $corps;


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
     * Set date.
     *
     * @param \DateTime $date
     *
     * @return Message
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date.
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set corps.
     *
     * @param string $corps
     *
     * @return Message
     */
    public function setCorps($corps)
    {
        $this->corps = $corps;

        return $this;
    }

    /**
     * Get corps.
     *
     * @return string
     */
    public function getCorps()
    {
        return $this->corps;
    }
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="messages_sent")
     * @ORM\JoinColumn(name="sender_id", referencedColumnName="id")
     */
    private $senders;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="messages_receiv" )
     * @ORM\JoinColumn(name="receptor_id", referencedColumnName="id")
     */
    private $recepters;

    /**
     * Set senders.
     *
     * @param \AppBundle\Entity\User $senders
     *
     * @return Message
     */
    public function setSenders(\AppBundle\Entity\User $senders)
    {
        $this->senders = $senders;

        return $this;
    }

    /**
     * Get senders.
     *
     * @return \AppBundle\Entity\User
     */
    public function getSenders()
    {
        return $this->senders;
    }

    /**
     * Set recepters.
     *
     * @param \AppBundle\Entity\User $recepters
     *
     * @return Message
     */
    public function setRecepters(\AppBundle\Entity\User $recepters)
    {
        $this->recepters = $recepters;

        return $this;
    }

    /**
     * Get recepters.
     *
     * @return \AppBundle\Entity\User
     */
    public function getRecepters()
    {
        return $this->recepters;
    }
}
