<?php
/**
 * @copyright 2012,2013 Binovo it Human Project, S.L.
 * @license http://www.opensource.org/licenses/bsd-license.php New-BSD
 */

namespace App\Entity;

/**
 * @ApiResource(
 *     output = BackupLocationOutput::class,
 *     collectionOperations= {"get"},
 *     itemOperations= {"get"}
 * )
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class BackupLocation
{
    const QUOTA_UNLIMITED = -1;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected string $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected ?string $host = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected string $directory;

    /**
     * Parallel jobs allowed for the location
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
     * Get id
     *
     * @return integer
     */
    public function getId(): int
    {
        return $this->id;
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
     * Set name
     *
     * @param string $name
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Get host
     *
     * @return string
     */
    public function getHost(): ?string
    {
        return $this->host;
    }

    /**
     * Set host
     *
     * @param string $host
     * @return void
     */
    public function setHost(string $host): void
    {
        $this->host = $host;
    }

    /**
     * Get directory
     *
     * @return string
     */
    public function getDirectory(): string
    {
        return $this->directory;
    }

    /**
     * Set directory
     *
     * @param string $directory
     * @return void
     */
    public function setDirectory(string $directory): void
    {
        $this->directory = $directory;
    }

    /**
     * Get effective directory
     * Returns the full path where the backup will be stored,
     * host and directory.
     *
     * @return string
     */
    public function getEffectiveDir(): string
    {
        if ("" == $this->getHost()) {
            return $this->getDirectory();
        } else {
            return sprintf('/net/%s%s', $this->getHost(), $this->getDirectory());
        }
    }
}
