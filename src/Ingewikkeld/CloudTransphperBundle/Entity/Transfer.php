<?php

namespace Ingewikkeld\CloudTransphperBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Transfer
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Ingewikkeld\CloudTransphperBundle\Entity\TransferRepository")
 */
class Transfer
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="senderName", type="string", length=255)
     */
    private $senderName;

    /**
     * @var string
     *
     * @ORM\Column(name="senderEmail", type="string", length=255)
     */
    private $senderEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="recipientName", type="string", length=255)
     */
    private $recipientName;

    /**
     * @var string
     *
     * @ORM\Column(name="recipientEmail", type="string", length=255)
     */
    private $recipientEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="link", type="string", length=255)
     */
    private $link;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetimetz")
     */
    private $created;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->created = new \DateTime('now');
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set senderName
     *
     * @param string $senderName
     * @return Transfer
     */
    public function setSenderName($senderName)
    {
        $this->senderName = $senderName;

        return $this;
    }

    /**
     * Get senderName
     *
     * @return string 
     */
    public function getSenderName()
    {
        return $this->senderName;
    }

    /**
     * Set senderEmail
     *
     * @param string $senderEmail
     * @return Transfer
     */
    public function setSenderEmail($senderEmail)
    {
        $this->senderEmail = $senderEmail;

        return $this;
    }

    /**
     * Get senderEmail
     *
     * @return string 
     */
    public function getSenderEmail()
    {
        return $this->senderEmail;
    }

    /**
     * Set recipientName
     *
     * @param string $recipientName
     * @return Transfer
     */
    public function setRecipientName($recipientName)
    {
        $this->recipientName = $recipientName;

        return $this;
    }

    /**
     * Get recipientName
     *
     * @return string 
     */
    public function getRecipientName()
    {
        return $this->recipientName;
    }

    /**
     * Set recipientEmail
     *
     * @param string $recipientEmail
     * @return Transfer
     */
    public function setRecipientEmail($recipientEmail)
    {
        $this->recipientEmail = $recipientEmail;

        return $this;
    }

    /**
     * Get recipientEmail
     *
     * @return string 
     */
    public function getRecipientEmail()
    {
        return $this->recipientEmail;
    }

    /**
     * Set link
     *
     * @param string $link
     * @return Transfer
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return string 
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Transfer
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Calculate a hash for this object based on the seed and object id
     *
     * @param string $seed
     * @return string
     */
    public function getHash($seed)
    {
        return md5($seed.$this->getId());
    }

    /**
     * Validate if the specified hash is for this object + the specified seed
     *
     * @param string $hash
     * @param string $seed
     * @return bool
     */
    public function validateHash($hash, $seed)
    {
        if ($hash == $this->getHash($seed)) {
            return true;
        }

        return false;
    }
}
