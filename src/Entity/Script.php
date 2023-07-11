<?php
/**
 * @copyright 2012,2013 Binovo it Human Project, S.L.
 * @license http://www.opensource.org/licenses/bsd-license.php New-BSD
 */

namespace App\Entity;

use App\Lib\Globals;
use DateTime;
use Doctrine\Common\Collections\Collection;
use RuntimeException;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @ApiResource(
 *     output = ScriptOutput::class,
 *     collectionOperations= {"get"},
 *     itemOperations= {"get"}
 * )
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\EntityListeners({"App\Listener\ScriptListener"})
 */
class Script
{
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
     * @ORM\Column(type="string", length=255, unique=true)
     */
    protected string $name;

    protected bool $deleteScriptFile = false;

    /**
     * @Assert\File(maxSize="6000000")
     */
    protected File $scriptFile;

    /**
     * Helper variable to remember the script time for PostRemove actions
     */
    protected $filesToRemove;

    /**
     * True if can run before the client
     * @ORM\Column(type="boolean")
     */
    protected bool $isClientPre;

    /**
     * True if can run before the job
     * @ORM\Column(type="boolean")
     */
    protected bool $isJobPre;

    /**
     * True if can run after the client
     * @ORM\Column(type="boolean")
     */
    protected bool $isClientPost;

    /**
     * True if can run after the job
     * @ORM\Column(type="boolean")
     */
    protected bool $isJobPost;


    /**
     * True if can run after the job
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected Datetime $lastUpdated;

    /**
     * @ORM\ManyToMany(targetEntity="Client", mappedBy="postScripts")
     */
    protected Collection $postClients;

    /**
     * @ORM\ManyToMany(targetEntity="Job", mappedBy="postScripts")
     */
    protected Collection $postJobs;

    /**
     * @ORM\ManyToMany(targetEntity="Client", mappedBy="preScripts")
     */
    protected Collection $preClients;

    /**
     * @ORM\ManyToMany(targetEntity="Job", mappedBy="preScripts")
     */
    protected Collection $preJobs;

    private mixed $scriptDirectory;

    /**
     * @param mixed $scriptDir
     */
    public function setScriptDirectory(mixed $scriptDir): void
    {
        $this->scriptDirectory = $scriptDir;
    }

    /**
     * Constructor
     */
    public function __construct($scriptDirectory)
    {
        $this->scriptDirectory = $scriptDirectory;
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload(): void
    {
        if ($this->scriptFile) {
            if (file_exists($this->getScriptPath()) && !unlink($this->getScriptPath())) {
                throw new RuntimeException("Error removing file " . $this->getScriptPath());
            }
            $this->scriptFile->move($this->getScriptDirectory(), $this->getScriptName());
            if (!chmod($this->getScriptPath(), 0755)) {
                throw new RuntimeException("Error setting file permission " . $this->getScriptPath());
            }
        } else {
            if (!file_exists($this->getScriptPath())) {
                throw new RuntimeException("Trying to create script entity without script file. Aborting.");
            }
        }
    }

    /**
     * @ORM\PreRemove()
     */
    public function prepareRemoveUpload(): void
    {
        $this->filesToRemove = array($this->getScriptPath());
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload(): void
    {
        foreach ($this->filesToRemove as $file) {
            if (file_exists($file)) {
                if (!Globals::delTree($file)) {
                    throw new RuntimeException("Error removing file " . $file);
                }
            }
        }
    }

    public function getScriptPath(): string
    {
        return sprintf('%s/%s', $this->getScriptDirectory(), $this->getScriptName());
    }

    public function getScriptDirectory()
    {
        return $this->scriptDirectory;
    }

    public function getScriptName(): string
    {
        return sprintf('%04d.script', $this->getId());
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Script
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
     * @return Script
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
     * Set scriptFile
     *
     * @param File $scriptFile
     * @return Script
     */
    public function setScriptFile(File $scriptFile): Script
    {
        $this->scriptFile = $scriptFile;

        return $this;
    }

    public function getScriptFileExists(): bool
    {
        return file_exists($this->getScriptPath());
    }

    /**
     * Get scriptFile
     *
     * @return string
     */
    public function getScriptFile(): string
    {
        return $this->scriptFile;
    }

    /**
     * Set isClientPre
     *
     * @param boolean $isClientPre
     * @return Script
     */
    public function setIsClientPre(bool $isClientPre): static
    {
        $this->isClientPre = $isClientPre;
        return $this;
    }

    /**
     * Get isClientPre
     *
     * @return boolean
     */
    public function getIsClientPre(): bool
    {
        return $this->isClientPre;
    }

    /**
     * Set isJobPre
     *
     * @param boolean $isJobPre
     * @return Script
     */
    public function setIsJobPre(bool $isJobPre): static
    {
        $this->isJobPre = $isJobPre;
        return $this;
    }

    /**
     * Get isJobPre
     *
     * @return boolean
     */
    public function getIsJobPre(): bool
    {
        return $this->isJobPre;
    }

    /**
     * Set isClientPost
     *
     * @param boolean $isClientPost
     * @return Script
     */
    public function setIsClientPost(bool $isClientPost): static
    {
        $this->isClientPost = $isClientPost;
        return $this;
    }

    /**
     * Get isClientPost
     *
     * @return boolean
     */
    public function getIsClientPost(): bool
    {
        return $this->isClientPost;
    }

    /**
     * Set isJobPost
     *
     * @param boolean $isJobPost
     * @return Script
     */
    public function setIsJobPost(bool $isJobPost): static
    {
        $this->isJobPost = $isJobPost;
        return $this;
    }

    /**
     * Get isJobPost
     *
     * @return boolean
     */
    public function getIsJobPost(): bool
    {
        return $this->isJobPost;
    }

    /**
     * Set lastUpdated
     *
     * @param DateTime $lastUpdated
     * @return Script
     */
    public function setLastUpdated(DateTime $lastUpdated): static
    {
        $this->lastUpdated = $lastUpdated;

        return $this;
    }

    /**
     * Get lastUpdated
     *
     * @return datetime
     */
    public function getLastUpdated(): DateTime
    {
        return $this->lastUpdated;
    }

    /**
     * Add postClients
     *
     * @param Client $postClients
     * @return Script
     */
    public function addPostClient(Client $postClients): static
    {
        $this->postClients[] = $postClients;
        return $this;
    }

    /**
     * Remove postClients
     *
     * @param Client $postClients
     */
    public function removePostClient(Client $postClients): void
    {
        $this->postClients->removeElement($postClients);
    }

    /**
     * Get postClients
     *
     * @return Collection
     */
    public function getPostClients(): Collection
    {
        return $this->postClients;
    }

    /**
     * Add postJobs
     *
     * @param Job $postJobs
     * @return Script
     */
    public function addPostJob(Job $postJobs): static
    {
        $this->postJobs[] = $postJobs;
        return $this;
    }

    /**
     * Remove postJobs
     *
     * @param Job $postJobs
     */
    public function removePostJob(Job $postJobs): void
    {
        $this->postJobs->removeElement($postJobs);
    }

    /**
     * Get postJobs
     *
     * @return Collection
     */
    public function getPostJobs(): Collection
    {
        return $this->postJobs;
    }

    /**
     * Add preClients
     *
     * @param Client $preClients
     * @return Script
     */
    public function addPreClient(Client $preClients): static
    {
        $this->preClients[] = $preClients;
        return $this;
    }

    /**
     * Remove preClients
     *
     * @param Client $preClients
     */
    public function removePreClient(Client $preClients): void
    {
        $this->preClients->removeElement($preClients);
    }

    /**
     * Get preClients
     *
     * @return Collection
     */
    public function getPreClients(): Collection
    {
        return $this->preClients;
    }

    /**
     * Add preJobs
     *
     * @param Job $preJobs
     * @return Script
     */
    public function addPreJob(Job $preJobs): static
    {
        $this->preJobs[] = $preJobs;
        return $this;
    }

    /**
     * Remove preJobs
     *
     * @param Job $preJobs
     */
    public function removePreJob(Job $preJobs): void
    {
        $this->preJobs->removeElement($preJobs);
    }

    /**
     * Get preJobs
     *
     * @return Collection
     */
    public function getPreJobs(): Collection
    {
        return $this->preJobs;
    }

    public function isUsed(): bool
    {
        $postClients = $this->getPostClients();
        $postJobs = $this->getPostJobs();
        $preClients = $this->getPreClients();
        $preJobs = $this->getPreJobs();

        return $postClients->count() != 0 ||
            $postJobs->count() != 0 ||
            $preClients->count() != 0 ||
            $preJobs->count() != 0;
    }
}