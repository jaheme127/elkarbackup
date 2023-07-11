<?php

namespace App\Api\Dto;


class ClientOutput
{
    private string $description;
    private int $id;
    private bool $isActive = true;
    private int $maxParallelJobs = 1;
    private string $name;
    private int $owner;
    private array $postScripts;
    private array $preScripts;
    private int $quota = -1;
    private string $rsyncLongArgs;
    private string $rsyncShortArgs;
    private string $sshArgs;
    private string $url;

    /**
     * @return string
     */
    public function getDescription(): string
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
     * @return integer
     */
    public function getOwner(): int
    {
        return $this->owner;
    }

    /**
     * @return array
     */
    public function getPostScripts(): array
    {
        return $this->postScripts;
    }

    /**
     * @return array
     */
    public function getPreScripts(): array
    {
        return $this->preScripts;
    }

    /**
     * Quota given in MiB
     *
     * @return integer
     */
    public function getQuota(): int
    {
        return $this->quota;
    }

    /**
     * @return string
     */
    public function getRsyncLongArgs(): string
    {
        return $this->rsyncLongArgs;
    }

    /**
     * @return string
     */
    public function getRsyncShortArgs(): string
    {
        return $this->rsyncShortArgs;
    }

    /**
     * @return string
     */
    public function getSshArgs(): string
    {
        return $this->sshArgs;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @param integer $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
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
     * @param array $postScripts
     */
    public function setPostScripts(array $postScripts): void
    {
        $this->postScripts = $postScripts;
    }

    /**
     * @param $preScripts
     */
    public function setPreScripts($preScripts): void
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
     * @param string $rsyncLongArgs
     */
    public function setRsyncLongArgs(string $rsyncLongArgs): void
    {
        $this->rsyncLongArgs = $rsyncLongArgs;
    }

    /**
     * @param string $rsyncShortArgs
     */
    public function setRsyncShortArgs(string $rsyncShortArgs): void
    {
        $this->rsyncShortArgs = $rsyncShortArgs;
    }

    /**
     * @param string $sshArgs
     */
    public function setSshArgs(string $sshArgs): void
    {
        $this->sshArgs = $sshArgs;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }
}
