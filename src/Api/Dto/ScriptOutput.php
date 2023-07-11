<?php

namespace App\Api\Dto;

class ScriptOutput
{
    private string $description;
    private int $id;
    private bool $isClientPost;
    private bool $isClientPre;
    private bool $isJobPost;
    private bool $isJobPre;
    private string $name;

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
    public function getIsClientPost(): bool
    {
        return $this->isClientPost;
    }

    /**
     * @return boolean
     */
    public function getIsClientPre(): bool
    {
        return $this->isClientPre;
    }

    /**
     * @return boolean
     */
    public function getIsJobPost(): bool
    {
        return $this->isJobPost;
    }

    /**
     * @return boolean
     */
    public function getIsJobPre(): bool
    {
        return $this->isJobPre;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param boolean $description
     */
    public function setDescription(bool $description): void
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
     * @param boolean $isClientPost
     */
    public function setIsClientPost(bool $isClientPost): void
    {
        $this->isClientPost = $isClientPost;
    }

    /**
     * @param boolean $isClientPre
     */
    public function setIsClientPre(bool $isClientPre): void
    {
        $this->isClientPre = $isClientPre;
    }

    /**
     * @param boolean $isJobPost
     */
    public function setIsJobPost(bool $isJobPost): void
    {
        $this->isJobPost = $isJobPost;
    }

    /**
     * @param boolean $isJobPre
     */
    public function setIsJobPre(bool $isJobPre): void
    {
        $this->isJobPre = $isJobPre;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

}

