<?php
/**
 * @copyright 2012,2013 Binovo it Human Project, S.L.
 * @license http://www.opensource.org/licenses/bsd-license.php New-BSD
 */

namespace App\Entity;

use DateTime;

/**
 * @ORM\Entity
 */
class LogRecord
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected int $id;

    /**
     * @ORM\Column(type="string")
     */
    protected string $channel;

    /**
     * @ORM\Column(type="datetime")
     */
    protected DateTime $dateTime;

    /**
     * @ORM\Column(type="integer")
     */
    protected int $level;

    /**
     * @ORM\Column(type="string")
     */
    protected string $levelName;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected string $link;

    /**
     * @ORM\Column(type="text")
     */
    protected string $message;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected string $source;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected int $userId;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected string $userName;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected string $logFile;

    public function __construct($channel, $dateTime, $level, $levelName, $message, $link = NULL, $source = NULL, $userId = NULL, $userName = NULL, $logFile = NULL)
    {
        $this->channel = $channel;
        $this->dateTime = $dateTime;
        $this->level = $level;
        $this->levelName = $levelName;
        $this->link = $link;
        $this->message = $message;
        $this->source = $source;
        $this->userId = $userId;
        $this->userName = $userName;
        $this->logFile = $logFile;
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
     * Set channel
     *
     * @param string $channel
     * @return LogRecord
     */
    public function setChannel(string $channel): static
    {
        $this->channel = $channel;

        return $this;
    }

    /**
     * Get channel
     *
     * @return string
     */
    public function getChannel(): string
    {
        return $this->channel;
    }

    /**
     * Set dateTime
     *
     * @param DateTime $dateTime
     * @return LogRecord
     */
    public function setDateTime(DateTime $dateTime): static
    {
        $this->dateTime = $dateTime;

        return $this;
    }

    /**
     * Get dateTime
     *
     * @return DateTime
     */
    public function getDateTime(): DateTime
    {
        return $this->dateTime;
    }

    /**
     * Set level
     *
     * @param integer $level
     * @return LogRecord
     */
    public function setLevel(int $level): static
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return integer
     */
    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * Set levelName
     *
     * @param string $levelName
     * @return LogRecord
     */
    public function setLevelName(string $levelName): static
    {
        $this->levelName = $levelName;

        return $this;
    }

    /**
     * Get levelName
     *
     * @return string
     */
    public function getLevelName(): string
    {
        return $this->levelName;
    }

    /**
     * Set message
     *
     * @param string $message
     * @return LogRecord
     */
    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * Set source
     *
     * @param string $source
     * @return LogRecord
     */
    public function setSource(string $source): static
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get source
     *
     * @return string|null
     */
    public function getSource(): ?string
    {
        return $this->source;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     * @return LogRecord
     */
    public function setUserId(int $userId): static
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return int|null
     */
    public function getUserId(): ?int
    {
        return $this->userId;
    }

    /**
     * Set userName
     *
     * @param string $userName
     * @return LogRecord
     */
    public function setUserName(string $userName): static
    {
        $this->userName = $userName;

        return $this;
    }

    /**
     * Get userName
     *
     * @return string|null
     */
    public function getUserName(): ?string
    {
        return $this->userName;
    }

    /**
     * Set link
     *
     * @param string $link
     * @return LogRecord
     */
    public function setLink(string $link): static
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return string|null
     */
    public function getLink(): ?string
    {
        return $this->link;
    }

    /**
     * Set logfile
     *
     * @param string $logfile
     * @return LogRecord
     */
    public function setLogfile(string $logfile): static
    {
        $this->logFile = $logfile;
        return $this;
    }

    /**
     * Get logfile
     *
     * @return string|null
     */
    public function getLogfile(): ?string
    {
        return $this->logFile;
    }

    public function getLogfilePath(): string
    {
        return sprintf('%s/%s/%s', $this->getLogDirectory(), 'jobs', $this->getLogfile());
    }

    public function getLogDirectory()
    {
        // Workaround to get kernel from Entity
        global $kernel;
        if ('AppCache' == get_class($kernel)) {
            $kernel = $kernel->getKernel();
        }
        return $kernel->getLogDir();
    }
}
