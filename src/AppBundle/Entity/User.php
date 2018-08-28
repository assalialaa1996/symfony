<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use JMS\Serializer\Annotation as JMSSerializer;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\Column;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 *
 * @UniqueEntity("email")
 * @UniqueEntity("username")
 * @JMSSerializer\ExclusionPolicy("all")
 * @JMSSerializer\AccessorOrder("custom", custom = {"id", "username", "email", "accounts"})
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @JMSSerializer\Expose
     * @JMSSerializer\Type("string")
     * @JMSSerializer\Groups({"users_all","users_summary"})
     */
    protected $id;

    /**
     * @JMSSerializer\Expose
     * @JMSSerializer\Type("string")
     * @JMSSerializer\Groups({"users_all","users_summary"})
     */
    protected $username;

    /**
     * @var string The email of the user.
     *
     * @JMSSerializer\Expose
     * @JMSSerializer\Type("string")
     * @JMSSerializer\Groups({"users_all","users_summary"})
     */
    protected $email;

    /**
     * One Customer has One Cart.
     * @OneToOne(targetEntity="ProfilBundle\Entity\Prof", mappedBy="user",cascade={"persist", "remove"})
     */
    private $prof;

    /**
     * One Customer has One Cart.
     * @OneToOne(targetEntity="EntrepriseBundle\Entity\Compte", mappedBy="user",cascade={"persist", "remove"})
     */
    private $compte;
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @var string The role of the user.
     * @ORM\Column(type="string")
     * @JMSSerializer\Expose
     * @JMSSerializer\Type("string")
     * @JMSSerializer\Groups({"users_all","users_summary"})
     */
    private $role;

    /**
     * Set role.
     *
     * @param string $role
     *
     * @return User
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role.
     *
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set prof.
     *
     * @param \ProfilBundle\Entity\Prof|null $prof
     *
     * @return User
     */
    public function setProf(\ProfilBundle\Entity\Prof $prof = null)
    {
        $this->prof = $prof;

        return $this;
    }

    /**
     * Get prof.
     *
     * @return \ProfilBundle\Entity\Prof|null
     */
    public function getProf()
    {
        return $this->prof;
    }
    /**
     * @var
     * @ORM\OneToMany(targetEntity="ChatBundle\Entity\Message" , mappedBy="senders")
     */

    private $messages_sent;
    /**
     * @var
     * @ORM\OneToMany(targetEntity="ChatBundle\Entity\Message" , mappedBy="recepters")
     */

    private $messages_receiv;

    /**
     * Set compte.
     *
     * @param \EntrepriseBundle\Entity\Compte|null $compte
     *
     * @return User
     */
    public function setCompte(\EntrepriseBundle\Entity\Compte $compte = null)
    {
        $this->compte = $compte;

        return $this;
    }

    /**
     * Get compte.
     *
     * @return \EntrepriseBundle\Entity\Compte|null
     */
    public function getCompte()
    {
        return $this->compte;
    }

    /**
     * Add messagesSent.
     *
     * @param \ChatBundle\Entity\Message $messagesSent
     *
     * @return User
     */
    public function addMessagesSent(\ChatBundle\Entity\Message $messagesSent)
    {
        $this->messages_sent[] = $messagesSent;

        return $this;
    }

    /**
     * Remove messagesSent.
     *
     * @param \ChatBundle\Entity\Message $messagesSent
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeMessagesSent(\ChatBundle\Entity\Message $messagesSent)
    {
        return $this->messages_sent->removeElement($messagesSent);
    }

    /**
     * Get messagesSent.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMessagesSent()
    {
        return $this->messages_sent;
    }

    /**
     * Add messagesReceiv.
     *
     * @param \ChatBundle\Entity\Message $messagesReceiv
     *
     * @return User
     */
    public function addMessagesReceiv(\ChatBundle\Entity\Message $messagesReceiv)
    {
        $this->messages_receiv[] = $messagesReceiv;

        return $this;
    }

    /**
     * Remove messagesReceiv.
     *
     * @param \ChatBundle\Entity\Message $messagesReceiv
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeMessagesReceiv(\ChatBundle\Entity\Message $messagesReceiv)
    {
        return $this->messages_receiv->removeElement($messagesReceiv);
    }

    /**
     * Get messagesReceiv.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMessagesReceiv()
    {
        return $this->messages_receiv;
    }
}
