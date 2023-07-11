<?php

namespace App\Api\Dto;

class JobOutput
{
    private int $backupLocation;
    private int $client;
    private string $description;
    private int $diskUsage = 0;
    private string $exclude;
    private int $id;
    private string $include;
    private bool $isActive = true;
    private int $minNotificationLevel;
    private string $name;
    private string $notificationsEmail;
    private array $notificationsTo = ["owner"];
    private string $path;
    private int $policy;
    private array $postScripts;
    private array $preScripts;
    private ?string $token = null;
    private bool $useLocalPermissions = true;

    /**
     * @return int
     */
    public function getBackupLocation(): int
    {
        return $this->backupLocation;
    }

    /**
     * @return int
     */
    public function getClient(): int
    {
        return $this->client;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return int
     */
    public function getDiskUsage(): int
    {
        return $this->diskUsage;
    }

    /**
     * @return string
     */
    public function getExclude(): string
    {
        return $this->exclude;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getInclude(): string
    {
        return $this->include;
    }

    /**
     * @return boolean
     */
    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    /**
     * @return int
     */
    public function getMinNotificationLevel(): int
    {
        return $this->minNotificationLevel;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getNotificationsEmail(): string
    {
        return $this->notificationsEmail;
    }

    /**
     * @return array|string
     */
    public function getNotificationsTo(): array|string
    {
        return $this->notificationsTo;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return int
     */
    public function getPolicy(): int
    {
        return $this->policy;
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
     * @return string
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @return boolean
     */
    public function getUseLocalPermissions(): bool
    {
        return $this->useLocalPermissions;
    }

    /**
     * @param int $backupLocation
     */
    public function setBackupLocation(int $backupLocation): void
    {
        $this->backupLocation = $backupLocation;
    }

    /**
     * @param int $client
     */
    public function setClient(int $client): void
    {
        $this->client = $client;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @param int $diskUsage
     */
    public function setDiskUsage(int $diskUsage): void
    {
        $this->diskUsage = $diskUsage;
    }

    /**
     * @param string $exclude
     */
    public function setExclude(string $exclude): void
    {
        $this->exclude = $exclude;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @param string $include
     */
    public function setInclude(string $include): void
    {
        $this->include = $include;
    }

    /**
     * @param boolean $isActive
     */
    public function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
    }

    /**
     * @param int $minNotificationLevel
     */
    public function setMinNotificationLevel(int $minNotificationLevel): void
    {
        $this->minNotificationLevel = $minNotificationLevel;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param string $notificationsEmail
     */
    public function setNotificationsEmail(string $notificationsEmail): void
    {
        $this->notificationsEmail = $notificationsEmail;
    }

    /**
     * @param array $notificationsTo
     */
    public function setNotificationsTo(array $notificationsTo): void
    {
        $this->notificationsTo = $notificationsTo;
    }

    /**
     * @param string $path
     */
    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    /**
     * @param int $policy
     */
    public function setPolicy(int $policy): void
    {
        $this->policy = $policy;
    }

    /**
     * @param array $postScripts
     */
    public function setPostScripts(array $postScripts): void
    {
        $this->postScripts = $postScripts;
    }

    /**
     * @param array $preScripts
     */
    public function setPreScripts(array $preScripts): void
    {
        $this->preScripts = $preScripts;
    }

    /**
     * @param string $token
     */
    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    /**
     * @param boolean $useLocalPermissions
     */
    public function setUseLocalPermissions(bool $useLocalPermissions): void
    {
        $this->useLocalPermissions = $useLocalPermissions;
    }

}

