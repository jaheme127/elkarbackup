<?php
/**
 * @copyright 2012,2013 Binovo it Human Project, S.L.
 * @license http://www.opensource.org/licenses/bsd-license.php New-BSD
 */

namespace App\Entity;

use App\Lib\Globals;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ApiResource(
 *     input  = JobInput::class,
 *     output = JobOutput::class,
 *     normalizationContext={
 *         "skip_null_values" = false
 *     },
 *     collectionOperations= {"get", "post"},
 *     itemOperations= {"get", "put", "delete"},
 * )
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Job
{
    const NOTIFY_TO_ADMIN = 'admin';
    const NOTIFY_TO_OWNER = 'owner';
    const NOTIFY_TO_EMAIL = 'email';

    const NOTIFICATION_LEVEL_ALL = 0;
    const NOTIFICATION_LEVEL_NONE = 1000;
    const NOTIFICATION_LEVEL_ERROR = 200;

    /**
     * @ApiFilter(JobClientFilter::class)
     * @ORM\ManyToOne(targetEntity="Client", inversedBy="jobs")
     */
    protected Client $client;

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
     * @ApiFilter(JobByNameFilter::class)
     * @ORM\Column(type="string", length=255)
     */
    protected string $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected string $notificationsEmail;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected string $notificationsTo = '["owner"]';

    /**
     * @ORM\Column(type="integer")
     */
    protected int $minNotificationLevel = self::NOTIFICATION_LEVEL_ERROR;

    /**
     * Include expressions
     * @ORM\Column(type="text", nullable=true)
     */
    protected string $include;

    /**
     * Exclude expressions
     * @ORM\Column(type="text", nullable=true)
     */
    protected string $exclude;

    /**
     * @ORM\ManyToOne(targetEntity="Policy")
     */
    protected Policy $policy;

    /**
     * @ORM\ManyToMany(targetEntity="Script", inversedBy="postJobs")
     * @ORM\JoinTable(name="JobScriptPost")
     */
    protected ArrayCollection $postScripts;

    /**
     * @ORM\ManyToMany(targetEntity="Script", inversedBy="preJobs")
     * @ORM\JoinTable(name="JobScriptPre")
     */
    protected ArrayCollection $preScripts;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected string $path;

    /**
     * Disk usage in KB.
     *
     * @ORM\Column(type="bigint")
     */
    protected int $diskUsage = 0;

    /**
     * Priority. Lower numbered jobs run first. Set to 2**31-1 for newly
     * created jobs so that they will run last.
     *
     * @ORM\Column(type="integer")
     */
    protected int $priority = 2147483647;

    /**
     * @ORM\Column(type="boolean")
     */
    protected bool $useLocalPermissions = true;

    /**
     * Helper variable to store the LogEntry to show on screen,
     * typically the last log LogRecord related to this client.
     */
    protected ?LogRecord $logEntry = null;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     * Job lastResult: ok, fail
     */
    protected ?string $lastResult = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * Security token for remote management
     */
    protected ?string $token = null;

    /**
     * @ORM\ManyToOne(targetEntity="BackupLocation")
     */
    protected BackupLocation $backupLocation;

    /**
     * Returns the full path of the snapshot directory
     */
    public function getSnapshotRoot(): string
    {
        return Globals::getSnapshotRoot($this->getClient()->getId(), $this);
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Job
     */
    public function setDescription(string $description): static
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
     * @return Job
     */
    public function setName(string $name): static
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
     * Set path
     *
     * @param string $path
     * @return Job
     */
    public function setPath(string $path): static
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl(): string
    {
        $clientUrl = $this->client->getUrl();
        if (empty($clientUrl)) {

            return $this->path;
        } else {
            // return url without ssh_args
            if (str_contains($clientUrl, 'ssh_args')) {
                $clientUrl = explode(" ", $clientUrl)[0];
            }
            return sprintf("%s:%s", $clientUrl, $this->path);
        }
    }

    /**
     * Set client
     *
     * @param Client|null $client
     * @return Job
     */
    public function setClient(Client $client = null): static
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * Set include
     *
     * @param string $include
     * @return Job
     */
    public function setInclude(string $include): static
    {
        $this->include = $include;

        return $this;
    }

    /**
     * Get include
     *
     * If the include list of the job is empty fetches the exclude list of the policy.
     *
     * @return string
     */
    public function getInclude(): string
    {
        $include = '';
        if (!empty($this->include)) {
            $include = $this->include;
        } else if ($this->policy) {
            $include = $this->policy->getInclude();
        }

        return $include;
    }

    /**
     * Set exclude
     *
     * @param string $exclude
     * @return Job
     */
    public function setExclude(string $exclude): Job
    {
        $this->exclude = $exclude;

        return $this;
    }

    /**
     * Get exclude.
     *
     * If the exclude list of the job is empty fetches the exclude list of the policy.
     *
     * @return string
     */
    public function getExclude(): string
    {
        $exclude = '';
        if (!empty($this->exclude)) {
            $exclude = $this->exclude;
        } else if ($this->policy) {
            $exclude = $this->policy->getExclude();
        }

        return $exclude;
    }

    /**
     * Set policy
     *
     * @param Policy|null $policy
     * @return Job
     */
    public function setPolicy(Policy $policy = null): static
    {
        $this->policy = $policy;

        return $this;
    }

    /**
     * Get policy
     *
     * @return Policy
     */
    public function getPolicy(): Policy
    {
        return $this->policy;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return Job
     */
    public function setIsActive(bool $isActive): static
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
     * Set notificationsTo
     *
     * @param string $notificationsTo
     * @return Job
     */
    public function setNotificationsTo(string $notificationsTo): static
    {
        $this->notificationsTo = json_encode(array_values((array)$notificationsTo));

        return $this;
    }

    /**
     * Get notificationsTo
     *
     * @return string
     */
    public function getNotificationsTo(): string
    {
        return json_decode($this->notificationsTo, true);
    }

    /**
     * Set notificationsEmail
     *
     * @param string $notificationsEmail
     * @return Job
     */
    public function setNotificationsEmail(string $notificationsEmail): static
    {
        $this->notificationsEmail = $notificationsEmail;

        return $this;
    }

    /**
     * Get notificationsEmail
     *
     * @return string
     */
    public function getNotificationsEmail(): string
    {
        return $this->notificationsEmail;
    }

    /**
     * Set minNotificationLevel
     *
     * @param integer $minNotificationLevel
     * @return Job
     */
    public function setMinNotificationLevel(int $minNotificationLevel): static
    {
        $this->minNotificationLevel = $minNotificationLevel;

        return $this;
    }

    /**
     * Get minNotificationLevel
     *
     * @return integer
     */
    public function getMinNotificationLevel(): int
    {
        return $this->minNotificationLevel;
    }

    /**
     * Set LogEntry
     *
     * @param LogRecord|null $logEntry
     * @return Job
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
     * Set diskUsage
     *
     * @param int $diskUsage
     * @return Job
     */
    public function setDiskUsage(int $diskUsage): static
    {
        $this->diskUsage = $diskUsage;

        return $this;
    }

    /**
     * Get diskUsage
     *
     * @return int
     */
    public function getDiskUsage(): int
    {
        return $this->diskUsage;
    }

    /**
     * Set Priority
     *
     * @param integer $Priority
     * @return Job
     */
    public function setPriority(int $Priority): static
    {
        $this->priority = $Priority;

        return $this;
    }

    /**
     * Get Priority
     *
     * @return integer
     */
    public function getPriority(): int
    {
        return $this->priority;
    }

    /**
     * Set useLocalPermissions
     *
     * @param boolean $useLocalPermissions
     * @return Job
     */
    public function setUseLocalPermissions(bool $useLocalPermissions): static
    {
        $this->useLocalPermissions = $useLocalPermissions;
        return $this;
    }

    /**
     * Get useLocalPermissions
     *
     * @return boolean
     */
    public function getUseLocalPermissions(): bool
    {
        return $this->useLocalPermissions;
    }

    public function __construct()
    {
        $this->postScripts = new ArrayCollection();
        $this->preScripts = new ArrayCollection();
    }

    /**
     * Add postScript
     *
     * @param Script $postScript
     * @return Job
     */
    public function addPostScript(Script $postScript): static
    {
        $this->postScripts[] = $postScript;
        return $this;
    }

    /**
     * Remove postScript
     *
     * @param Script $postScript
     */
    public function removePostScript(Script $postScript): void
    {
        $this->postScripts->removeElement($postScript);
    }

    /**
     * Add preScripts
     *
     * @param Script $preScripts
     * @return Job
     */
    public function addPreScript(Script $preScripts): static
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
     * Get postScripts
     *
     * @return Collection
     */
    public function getPostScripts(): Collection
    {
        return $this->postScripts;
    }

    /**
     * Set last result
     *
     * @param string $lastResult
     *
     * @return Job
     */
    public function setLastResult(string $lastResult): static
    {
        $this->lastResult = $lastResult;

        return $this;
    }

    /**
     * Get last result
     *
     * @return string|null
     */
    public function getLastResult(): ?string
    {
        return $this->lastResult;
    }

    /**
     * Set token
     *
     * @param string $token
     *
     * @return Job
     */
    public function setToken(string $token): static
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string|null
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * Get backupLocation
     * @return BackupLocation
     */
    public function getBackupLocation(): BackupLocation
    {
        return $this->backupLocation;
    }

    /**
     * Set backupLocation
     *
     * @param BackupLocation $backupLocation
     * @return void
     */
    public function setBackupLocation(BackupLocation $backupLocation): void
    {
        $this->backupLocation = $backupLocation;
    }
}
