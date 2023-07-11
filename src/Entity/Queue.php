<?php
/**
 * @copyright 2012,2013 Binovo it Human Project, S.L.
 * @license http://www.opensource.org/licenses/bsd-license.php New-BSD
 */

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Entity
 */
class Queue
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected int $id;

    /**
     * @ORM\ManyToOne(targetEntity="Job")
     */
    protected Job $job;

    /**
     * @ORM\Column(type="datetime")
     */
    protected DateTime $date;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected DateTime $runningSince;

    /**
     * @ORM\Column(type="integer")
     */
    protected int $priority;

    /**
     * Variable to show the state in the queue
     *
     * @ORM\Column(type="string",length=255, nullable=false)
     */
    protected string $state;

    /**
     * Variable to show if the task is aborted and its state.
     *
     * @ORM\Column(type="boolean")
     */
    protected bool $aborted;

    /**
     * Data generated during the execution
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected string $data;

    public function __construct($job = null)
    {
        $this->date = new DateTime();
        $this->job = $job;
        $this->priority = $job->getPriority();
        $this->state = 'QUEUED';
        $this->aborted = false;
    }

    /**
     * Get id
     * @return integer
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get job
     * @return Job
     */
    public function getJob(): Job
    {
        return $this->job;
    }

    /**
     * Set job
     *
     * @param Job $job
     * @return void
     */
    public function setJob(Job $job): void
    {
        $this->job = $job;
    }

    /**
     * Get date
     *
     * @return datetime
     */
    public function getDate(): DateTime
    {
        return $this->date;
    }

    /**
     * Set date
     *
     * @param datetime $date
     * @return void
     */
    public function setDate(DateTime $date): void
    {
        $this->date = $date;
    }

    /**
     * Get running since
     *
     * @return datetime
     */
    public function getRunningSince(): DateTime
    {
        return $this->runningSince;
    }

    /**
     * Set running since
     *
     * @param datetime $runningSince
     * @return void
     */
    public function setRunningSince(DateTime $runningSince): void
    {
        $this->runningSince = $runningSince;
    }

    /**
     * Get priority
     *
     * @return int
     */
    public function getPriority(): int
    {
        return $this->priority;
    }

    /**
     * Set priority
     *
     * @param integer $priority
     *
     * @return void
     */
    public function setPriority(int $priority): void
    {
        $this->priority = $priority;
    }

    /**
     * Get State
     *
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * Set State
     *
     * @param string $state
     *
     * @return void
     */
    public function setState(string $state): void
    {
        $this->state = $state;
    }

    /**
     * Get aborted
     *
     * @return bool
     */
    public function getAborted(): bool
    {
        return $this->aborted;
    }

    /**
     * Set aborted
     *
     * @param boolean $aborted
     *
     * @return void
     */
    public function setAborted(bool $aborted): void
    {
        $this->aborted = $aborted;
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData(): array
    {
        return json_decode($this->data, true);
    }

    /**
     * Set data
     *
     * @param array $data
     *
     * @return void
     */
    public function setData(array $data): void
    {
        $this->data = json_encode($data);
    }
}