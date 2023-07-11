<?php
/**
 * @copyright 2012,2013 Binovo it Human Project, S.L.
 * @license http://www.opensource.org/licenses/bsd-license.php New-BSD
 */

namespace App\Entity;

use DateTime;

/**
 *
 * @ORM\Entity
 */
class Message
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", name="origin", length=32)
     */
    private string $from;

    /**
     * @ORM\Column(type="string", name="destination", length=32)
     */
    private string $to;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private string $message;

    /**
     * @ORM\Column(type="datetime")
     */
    protected DateTime $dateTime;

    public function __construct($from = null, $to = null, $message = null)
    {
        $this->dateTime = new DateTime();
        $this->from = $from;
        $this->message = $message;
        $this->to = $to;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set from
     *
     * @param string $from
     * @return Message
     */
    public function setFrom(string $from): static
    {
        $this->from = $from;
        return $this;
    }

    /**
     * Get from
     *
     * @return string|null
     */
    public function getFrom(): ?string
    {
        return $this->from;
    }

    /**
     * Set to
     *
     * @param string $to
     * @return Message
     */
    public function setTo(string $to): static
    {
        $this->to = $to;
        return $this;
    }

    /**
     * Get to
     *
     * @return string|null
     */
    public function getTo(): ?string
    {
        return $this->to;
    }

    /**
     * Set message
     *
     * @param string $message
     * @return Message
     */
    public function setMessage(string $message): static
    {
        $this->message = $message;
        return $this;
    }

    /**
     * Get message
     *
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * Set dateTime
     *
     * @param datetime $dateTime
     * @return Message
     */
    public function setDateTime(DateTime $dateTime): static
    {
        $this->dateTime = $dateTime;
        return $this;
    }

    /**
     * Get dateTime
     *
     * @return datetime
     */
    public function getDateTime(): DateTime
    {
        return $this->dateTime;
    }
}