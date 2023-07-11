<?php
/**
 * @copyright 2012,2013 Binovo it Human Project, S.L.
 * @license http://www.opensource.org/licenses/bsd-license.php New-BSD
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ApiResource(
 *     input  = ClientInput::class,
 *     output = ClientOutput::class,
 *     normalizationContext={
 *         "skip_null_values" = false
 *     },
 *     collectionOperations= {"get", "post"},
 *     itemOperations= {"get", "put", "delete"},
 * )
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Client
{
    const QUOTA_UNLIMITED = -1;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected string $description;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected int $id;

    /**
     * @ORM\Column(type="boolean")
     */
    protected bool $isActive = true;

    /**
     * @ORM\OneToMany(targetEntity="Job", mappedBy="client", cascade={"remove"})
     */
    protected ArrayCollection $jobs;

    /**
     * @ApiFilter(ClientByNameFilter::class)
     * @ORM\Column(type="string", length=255, unique=true)
     */
    protected string $name;

    /**
     * @ORM\ManyToMany(targetEntity="Script", inversedBy="postClients")
     * @ORM\JoinTable(name="ClientScriptPost")
     */
    protected Collection $postScripts;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected string $url = '';

    /**
     * @ORM\ManyToMany(targetEntity="Script", inversedBy="preClients")
     * @ORM\JoinTable(name="ClientScriptPre")
     */
    protected Collection $preScripts;

    /**
     * Quota in KB. -1 means no limit, which is the default.
     *
     * @ORM\Column(type="bigint")
     */
    protected int $quota = self::QUOTA_UNLIMITED;

    /**
     * Helper variable to store the LogEntry to show on screen,
     * typically the last log LogRecord related to this client.
     */
    protected ?LogRecord $logEntry = null;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     */
    protected User $owner;

    /**
     * Rsnapshot ssh_args parameter
     *
     * @ORM\Column(type="string",length=255, nullable=true)
     */
    protected string $sshArgs;

    /**
     * Rsnapshot rsync_short_args parameter
     *
     * @ORM\Column(type="string",length=255, nullable=true)
     */
    protected string $rsyncShortArgs;

    /**
     * Rsnapshot rsync_long_args parameter
     *
     * @ORM\Column(type="string",length=255, nullable=true)
     */
    protected string $rsyncLongArgs;

    /**
     * Variable to show the state in the queue
     *
     * @ORM\Column(type="string",length=255, nullable=false)
     */
    protected string $state;

    /**
     * Parallel jobs allowed for the client
     *
     * @ORM\Column(type="integer", nullable=false)
     * @Assert\Regex(
     *     pattern     = "/^[1-9]\d*$/i",
     *     htmlPattern = "^[1-9]\d*$",
     *     message="Max parallel jobs value must be a positive integer"
     * )
     */
    protected int $maxParallelJobs = 1;

    /**
     * Data generated during the execution
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected string $data;

    /**
     * Get max parallel jobs
     *
     * @return integer
     */
    public function getMaxParallelJobs(): int
    {
        return $this->maxParallelJobs;
    }

    /**
     * Set max parallel jobs
     *
     * @param integer $maxParallelJobs
     * @return void
     */
    public function setMaxParallelJobs(int $maxParallelJobs): void
    {
        $this->maxParallelJobs = $maxParallelJobs;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->jobs = new ArrayCollection();
        $this->state = "NOT READY";
    }

    /**
     * Returns true if the backup directory exists
     */
    public function hasBackups(): bool
    {
        $hasBackups = false;
        $jobs = $this->getJobs();
        foreach ($jobs as $job) {
            $backupLocation = $job->getBackupLocation();
            $directory = sprintf('%s/%04d', $backupLocation->getDirectory(), $this->getId());
            if (is_dir($directory)) {
                $hasBackups = true;
                break;
            }
        }

        return $hasBackups;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Client
     */
    public function setDescription(string $description): Client
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
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
     * Set name
     *
     * @param string $name
     * @return Client
     */
    public function setName(string $name): Client
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Client
     */
    public function setUrl(string $url): Client
    {
        if ($url != null) {
            $this->url = $url;
        } else {
            $this->url = '';
        }

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * Add jobs
     *
     * @param Job $jobs
     * @return Client
     */
    public function addJob(Job $jobs): Client
    {
        $this->jobs[] = $jobs;

        return $this;
    }

    /**
     * Remove jobs
     *
     * @param Job $jobs
     */
    public function removeJob(Job $jobs): void
    {
        $this->jobs->removeElement($jobs);
    }

    /**
     * Get jobs
     *
     * @return Collection
     */
    public function getJobs(): Collection
    {
        return $this->jobs;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return Client
     */
    public function setIsActive(bool $isActive): Client
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    /**
     * Set LogEntry
     *
     * @param LogRecord|null $logEntry
     * @return Client
     */
    public function setLogEntry(LogRecord $logEntry = null): static
    {
        $this->logEntry = $logEntry;

        return $this;
    }

    /**
     * Get LogEntry
     *
     * @return LogRecord|null
     */
    public function getLogEntry(): ?LogRecord
    {
        return $this->logEntry;
    }

    /**
     * Get diskUsage
     *
     * @return int
     */
    public function getDiskUsage(): int
    {
        $du = 0;
        foreach ($this->jobs as $job) {
            $du += $job->getDiskUsage();
        }
        return $du;
    }

    /**
     * Set quota
     *
     * @param int $quota
     * @return Client
     */
    public function setQuota(int $quota): Client
    {
        $this->quota = $quota;
        return $this;
    }

    /**
     * Get quota
     *
     * @return int
     */
    public function getQuota(): int
    {
        return $this->quota;
    }

    /**
     * Add postScripts
     *
     * @param Script $postScripts
     * @return Client
     */
    public function addPostScript(Script $postScripts): Client
    {
        $this->postScripts[] = $postScripts;
        return $this;
    }

    /**
     * Remove postScripts
     *
     * @param Script $postScripts
     */
    public function removePostScript(Script $postScripts): void
    {
        $this->postScripts->removeElement($postScripts);
    }

    /**
     * Get postScripts
     *
     * @return Collection
     */
    public function getPostScripts(): Collection
    {
        return $this->postScripts;
    }

    /**
     * Add preScripts
     *
     * @param Script $preScripts
     * @return Client
     */
    public function addPreScript(Script $preScripts): Client
    {
        $this->preScripts[] = $preScripts;
        return $this;
    }

    /**
     * Remove preScripts
     *
     * @param Script $preScripts
     */
    public function removePreScript(Script $preScripts): void
    {
        $this->preScripts->removeElement($preScripts);
    }

    /**
     * Get preScripts
     *
     * @return Collection
     */
    public function getPreScripts(): Collection
    {
        return $this->preScripts;
    }

    /**
     * Set owner
     *
     * @param User|null $owner
     *
     * @return Client
     */
    public function setOwner(User $owner = null): Client
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return User
     */
    public function getOwner(): User
    {
        return $this->owner;
    }

    /**
     * Set sshArgs
     *
     * @param string|null $sshArgs
     *
     * @return Client
     */
    public function setSshArgs(string $sshArgs = null): Client
    {
        $this->sshArgs = $sshArgs;

        return $this;
    }

    /**
     * Get sshArgs
     *
     * @return string
     */
    public function getSshArgs(): string
    {
        return $this->sshArgs;
    }

    /**
     * Set rsyncShortArgs
     *
     * @param string|null $rsyncShortArgs
     *
     * @return Client
     */
    public function setRsyncShortArgs(string $rsyncShortArgs = null): static
    {
        $this->rsyncShortArgs = $rsyncShortArgs;

        return $this;
    }

    /**
     * Get rsyncShortArgs
     *
     * @return string
     */
    public function getRsyncShortArgs(): string
    {
        return $this->rsyncShortArgs;
    }

    /**
     * Set rsyncLongArgs
     *
     * @param string|null $rsyncLongArgs
     *
     * @return Client
     */
    public function setRsyncLongArgs(string $rsyncLongArgs = null): Client
    {
        $this->rsyncLongArgs = $rsyncLongArgs;

        return $this;
    }

    /**
     * Get rsyncLongArgs
     *
     * @return string
     */
    public function getRsyncLongArgs(): string
    {
        return $this->rsyncLongArgs;
    }

    /**
     * Get state
     *
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * Set state
     * @param string $state
     *
     */
    public function setState(string $state): void
    {
        $this->state = $state;
    }

    /**
     * Get data
     *
     * @return string
     */
    public function getData(): string
    {
        return json_decode($this->data, true);
    }

    /**
     * Set data
     *
     * @param string $data
     *
     * @return Client
     */
    public function setData(string $data): Client
    {
        if (!empty($data)) {
            $this->data = serialize($data);
        }
        return $this;
    }
}
