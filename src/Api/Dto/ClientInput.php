<?php

namespace App\Api\Dto;

class ClientInput
{
    private ?string $description;
    private int $id;
    private bool $isActive = true;
    private int $maxParallelJobs = 1;
    private string $name;
    private ?int $owner;
    private array $postScripts = [];
    private array $preScripts = [];
    private int $quota = -1;
    private ?string $rsyncLongArgs;
    private ?string $rsyncShortArgs;
    private ?string $sshArgs;
    private ?string $url;

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return integer
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return boolean
     */
    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    /**
     * @return integer
     */
    public function getMaxParallelJobs(): int
    {
        return $this->maxParallelJobs;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int|null
     */
    public function getOwner(): ?int
    {
        return $this->owner;
    }

    /**
     * @return array|null
     */
    public function getPostScripts(): ?array
    {
        return $this->postScripts;
    }

    /**
     * @return array|null
     */
    public function getPreScripts(): ?array
    {
        return $this->preScripts;
    }

    /**
     * Quota given in MiB
     *
     * @return int
     */
    public function getQuota(): int
    {
        return $this->quota;
    }

    /**
     * @return string|null
     */
    public function getRsyncLongArgs(): ?string
    {
        return $this->rsyncLongArgs;
    }

    /**
     * @return string|null
     */
    public function getRsyncShortArgs(): ?string
    {
        return $this->rsyncShortArgs;
    }

    /**
     * @return string|null
     */
    public function getSshArgs(): ?string
    {
        return $this->sshArgs;
    }

    /**
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @param boolean $isActive
     */
    public function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
    }

    /**
     * @param integer $maxParallelJobs
     */
    public function setMaxParallelJobs(int $maxParallelJobs): void
    {
        $this->maxParallelJobs = $maxParallelJobs;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param integer $owner
     */
    public function setOwner(int $owner): void
    {
        $this->owner = $owner;
    }

    /**
     * @param array|null $postScripts
     */
    public function setPostScripts(?array $postScripts): void
    {
        $this->postScripts = $postScripts;
    }

    /**
     * @param array|null $preScripts
     */
    public function setPreScripts(?array $preScripts): void
    {
        $this->preScripts = $preScripts;
    }

    /**
     * @param integer $quota
     */
    public function setQuota(int $quota): void
    {
        $this->quota = $quota;
    }

    /**
     * @param string|null $rsyncLongArgs
     */
    public function setRsyncLongArgs(?string $rsyncLongArgs): void
    {
        $this->rsyncLongArgs = $rsyncLongArgs;
    }

    /**
     * @param string|null $rsyncShortArgs
     */
    public function setRsyncShortArgs(?string $rsyncShortArgs): void
    {
        $this->rsyncShortArgs = $rsyncShortArgs;
    }

    /**
     * @param string|null $sshArgs
     */
    public function setSshArgs(?string $sshArgs): void
    {
        $this->sshArgs = $sshArgs;
    }

    /**
     * @param string|null $url
     */
    public function setUrl(?string $url): void
    {
        $this->url = $url;
    }
}

