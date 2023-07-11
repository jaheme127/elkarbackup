<?php
/**
 * @copyright 2012,2013 Binovo it Human Project, S.L.
 * @license http://www.opensource.org/licenses/bsd-license.php New-BSD
 */

namespace App\Entity;

use DateTime;

/**
 * @ApiResource(
 *     output = PolicyOutput::class,
 *     collectionOperations= {"get"},
 *     itemOperations= {"get"}
 * )
 * @ORM\Entity
 */
class Policy
{
    private array $allRetains = array('Yearly', 'Monthly', 'Weekly', 'Daily', 'Hourly');

    /**
     * Returns the retains that should run in $time in the right order.
     * @param  DateTime $time
     * @return array of strings
     */
    public function getRunnableRetains(DateTime $time): array
    {
        $allRetains = $this->allRetains;
        $retains = array();
        list($year, $month, $day, $hour, $dayOfWeek) = explode('-', $time->format('Y-n-j-H:i-N'));
        foreach ($allRetains as $retain) {
            $getCount       = "get{$retain}Count";
            $getDaysOfMonth = "get{$retain}DaysOfMonth";
            $getDaysOfWeek  = "get{$retain}DaysOfWeek";
            $getHours       = "get{$retain}Hours";
            $getMonth       = "get{$retain}Months";
            if ($this->$getCount() != 0 &&
                ($this->$getDaysOfMonth() == null || preg_match('/^'.$this->$getDaysOfMonth().'$/', $day)) &&
                ($this->$getDaysOfWeek()  == null || preg_match('/^'.$this->$getDaysOfWeek() .'$/', $dayOfWeek)) &&
                ($this->$getHours()       == null || preg_match('/^'.$this->$getHours()      .'$/', $hour)) &&
                ($this->$getMonth()       == null || preg_match('/^'.$this->$getMonth()      .'$/', $month))) {
                $retains[] = $retain;
            }
        }

        return $retains;
    }

    /**
     * Returns the retains for this Policy in the order they should have in the config file.
     * @return array of array(string, int)
     */
    public function getRetains(): array
    {
        $allRetains = $this->allRetains;
        $retains[] = array();
        foreach ($allRetains as $retain) {
            $getCount       = "get{$retain}Count";
            if ($this->$getCount() != 0) {
                $retains[] = array($retain, $this->$getCount());
            }
        }

        return array_reverse($retains);
    }

    /**
     * Returns true if $retain only rotates the backups and doesn't
     * try to fetch data from the client.
     */
    public function isRotation($retain): bool
    {
        $retains = $this->getRetains();
        return !(count($retains) && $retains[0][0] == $retain);
    }

    /**
     * @Assert\IsTrue(message = "You forgot to specify hours during the day")
     */
    public function isHourlyHoursValid(): bool
    {
        return empty($this->hourlyCount) || !empty($this->hourlyHours);
    }

    /**
     * True if running the retain $retain requires a previous sync
     */
    public function mustSync($retain): bool
    {
        $retains = $this->getRetains();
        return $this->getSyncFirst() && count($retains) && $retains[0][0] == $retain;
    }

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected int $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected string $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected string $name;

    /**
     * Regex to match the time (H:i format in date) for hourly intervals.
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected string $hourlyHours;

    /**
     * Regex to match the day of month (d format in date) for hourly intervals.
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected string $hourlyDaysOfMonth;

    /**
     * Regex to match the day of the week (N format in date) for hourly intervals.
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected string $hourlyDaysOfWeek;

    /**
     * Regex to match the month (m format in date) for hourly intervals.
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected string $hourlyMonths;

    /**
     * Amount retains.
     * @ORM\Column(type="integer")
     */
    protected int $hourlyCount = 0;

    /**
     * Regex to match the time (H:i format in date) for daily intervals.
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected string $dailyHours;

    /**
     * Regex to match the day of month (d format in date) for daily intervals.
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected string $dailyDaysOfMonth;

    /**
     * Regex to match the day of the week (N format in date) for daily intervals.
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected string $dailyDaysOfWeek;

    /**
     * Regex to match the month (m format in date) for daily intervals.
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected string $dailyMonths;

    /**
     * Amount retains.
     * @ORM\Column(type="integer")
     */
    protected int $dailyCount = 0;

    /**
     * Regex to match the time (H:i format in date) for weekly intervals.
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected string $weeklyHours;

    /**
     * Regex to match the day of month (d format in date) for weekly intervals.
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected string $weeklyDaysOfMonth;

    /**
     * Regex to match the day of the week (N format in date) for weekly intervals.
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected string $weeklyDaysOfWeek;

    /**
     * Regex to match the month (m format in date) for weekly intervals.
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected string $weeklyMonths;

    /**
     * Amount retains.
     * @ORM\Column(type="integer")
     */
    protected int $weeklyCount = 0;

    /**
     * Regex to match the time (H:i format in date) for monthly intervals.
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected string $monthlyHours;

    /**
     * Regex to match the day of month (d format in date) for monthly intervals.
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected string $monthlyDaysOfMonth;

    /**
     * Regex to match the day of the week (N format in date) for monthly intervals.
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected string $monthlyDaysOfWeek;

    /**
     * Regex to match the month (m format in date) for monthly intervals.
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected string $monthlyMonths;

    /**
     * Amount retains
     * @ORM\Column(type="integer")
     */
    protected int $monthlyCount = 0;

    /**
     * Regex to match the time (H:i format in date) for yearly intervals.
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected string $yearlyHours;

    /**
     * Regex to match the day of month (d format in date) for yearly intervals.
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected string $yearlyDaysOfMonth;

    /**
     * Regex to match the day of the week (N format in date) for yearly intervals.
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected string $yearlyDaysOfWeek;

    /**
     * Regex to match the month (m format in date) for yearly intervals.
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected string $yearlyMonths;

    /**
     * Amount retains
     * @ORM\Column(type="integer")
     */
    protected int $yearlyCount = 0;

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
     * Whether to use rsnapshot sync_first option
     * @ORM\Column(type="boolean")
     */
    protected bool $syncFirst;

    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Policy
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
     */
    public function setName(string $name): void
    {
        $this->name = $name;
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
     * Set hourlyHours
     *
     * @param string $hourlyHours
     * @return Policy
     */
    public function setHourlyHours(string $hourlyHours): static
    {
        $this->hourlyHours = $hourlyHours;

        return $this;
    }

    /**
     * Get hourlyHours
     *
     * @return string
     */
    public function getHourlyHours(): string
    {
        return $this->hourlyHours;
    }

    /**
     * Set hourlyDaysOfMonth
     *
     * @param string $hourlyDaysOfMonth
     * @return Policy
     */
    public function setHourlyDaysOfMonth(string $hourlyDaysOfMonth): static
    {
        $this->hourlyDaysOfMonth = $hourlyDaysOfMonth;

        return $this;
    }

    /**
     * Get hourlyDaysOfMonth
     *
     * @return string
     */
    public function getHourlyDaysOfMonth(): string
    {
        return $this->hourlyDaysOfMonth;
    }

    /**
     * Set hourlyDaysOfWeek
     *
     * @param string $hourlyDaysOfWeek
     * @return Policy
     */
    public function setHourlyDaysOfWeek(string $hourlyDaysOfWeek): static
    {
        $this->hourlyDaysOfWeek = $hourlyDaysOfWeek;

        return $this;
    }

    /**
     * Get hourlyDaysOfWeek
     *
     * @return string
     */
    public function getHourlyDaysOfWeek(): string
    {
        return $this->hourlyDaysOfWeek;
    }

    /**
     * Set hourlyMonths
     *
     * @param string $hourlyMonths
     * @return Policy
     */
    public function setHourlyMonths(string $hourlyMonths): static
    {
        $this->hourlyMonths = $hourlyMonths;

        return $this;
    }

    /**
     * Get hourlyMonths
     *
     * @return string
     */
    public function getHourlyMonths(): string
    {
        return $this->hourlyMonths;
    }

    /**
     * Set hourlyCount
     *
     * @param integer $hourlyCount
     * @return Policy
     */
    public function setHourlyCount(int $hourlyCount): static
    {
        $this->hourlyCount = $hourlyCount;

        return $this;
    }

    /**
     * Get hourlyCount
     *
     * @return integer
     */
    public function getHourlyCount(): int
    {
        return $this->hourlyCount;
    }

    /**
     * Set dailyHours
     *
     * @param string $dailyHours
     * @return Policy
     */
    public function setDailyHours(string $dailyHours): static
    {
        $this->dailyHours = $dailyHours;

        return $this;
    }

    /**
     * Get dailyHours
     *
     * @return string
     */
    public function getDailyHours(): string
    {
        return $this->dailyHours;
    }

    /**
     * Set dailyDaysOfMonth
     *
     * @param string $dailyDaysOfMonth
     * @return Policy
     */
    public function setDailyDaysOfMonth(string $dailyDaysOfMonth): static
    {
        $this->dailyDaysOfMonth = $dailyDaysOfMonth;

        return $this;
    }

    /**
     * Get dailyDaysOfMonth
     *
     * @return string
     */
    public function getDailyDaysOfMonth(): string
    {
        return $this->dailyDaysOfMonth;
    }

    /**
     * Set dailyDaysOfWeek
     *
     * @param string $dailyDaysOfWeek
     * @return Policy
     */
    public function setDailyDaysOfWeek(string $dailyDaysOfWeek): static
    {
        $this->dailyDaysOfWeek = $dailyDaysOfWeek;

        return $this;
    }

    /**
     * Get dailyDaysOfWeek
     *
     * @return string
     */
    public function getDailyDaysOfWeek(): string
    {
        return $this->dailyDaysOfWeek;
    }

    /**
     * Set dailyMonths
     *
     * @param string $dailyMonths
     * @return Policy
     */
    public function setDailyMonths(string $dailyMonths): static
    {
        $this->dailyMonths = $dailyMonths;

        return $this;
    }

    /**
     * Get dailyMonths
     *
     * @return string
     */
    public function getDailyMonths(): string
    {
        return $this->dailyMonths;
    }

    /**
     * Set dailyCount
     *
     * @param integer $dailyCount
     * @return Policy
     */
    public function setDailyCount(int $dailyCount): static
    {
        $this->dailyCount = $dailyCount;

        return $this;
    }

    /**
     * Get dailyCount
     *
     * @return integer
     */
    public function getDailyCount(): int
    {
        return $this->dailyCount;
    }

    /**
     * Set weeklyHours
     *
     * @param string $weeklyHours
     * @return Policy
     */
    public function setWeeklyHours(string $weeklyHours): static
    {
        $this->weeklyHours = $weeklyHours;

        return $this;
    }

    /**
     * Get weeklyHours
     *
     * @return string
     */
    public function getWeeklyHours(): string
    {
        return $this->weeklyHours;
    }

    /**
     * Set weeklyDaysOfMonth
     *
     * @param string $weeklyDaysOfMonth
     * @return Policy
     */
    public function setWeeklyDaysOfMonth(string $weeklyDaysOfMonth): static
    {
        $this->weeklyDaysOfMonth = $weeklyDaysOfMonth;

        return $this;
    }

    /**
     * Get weeklyDaysOfMonth
     *
     * @return string
     */
    public function getWeeklyDaysOfMonth(): string
    {
        return $this->weeklyDaysOfMonth;
    }

    /**
     * Set weeklyDaysOfWeek
     *
     * @param string $weeklyDaysOfWeek
     * @return Policy
     */
    public function setWeeklyDaysOfWeek(string $weeklyDaysOfWeek): static
    {
        $this->weeklyDaysOfWeek = $weeklyDaysOfWeek;

        return $this;
    }

    /**
     * Get weeklyDaysOfWeek
     *
     * @return string
     */
    public function getWeeklyDaysOfWeek(): string
    {
        return $this->weeklyDaysOfWeek;
    }

    /**
     * Set weeklyMonths
     *
     * @param string $weeklyMonths
     * @return Policy
     */
    public function setWeeklyMonths(string $weeklyMonths): static
    {
        $this->weeklyMonths = $weeklyMonths;

        return $this;
    }

    /**
     * Get weeklyMonths
     *
     * @return string
     */
    public function getWeeklyMonths(): string
    {
        return $this->weeklyMonths;
    }

    /**
     * Set weeklyCount
     *
     * @param integer $weeklyCount
     * @return Policy
     */
    public function setWeeklyCount(int $weeklyCount): static
    {
        $this->weeklyCount = $weeklyCount;

        return $this;
    }

    /**
     * Get weeklyCount
     *
     * @return integer
     */
    public function getWeeklyCount(): int
    {
        return $this->weeklyCount;
    }

    /**
     * Set monthlyHours
     *
     * @param string $monthlyHours
     * @return Policy
     */
    public function setMonthlyHours(string $monthlyHours): static
    {
        $this->monthlyHours = $monthlyHours;

        return $this;
    }

    /**
     * Get monthlyHours
     *
     * @return string
     */
    public function getMonthlyHours(): string
    {
        return $this->monthlyHours;
    }

    /**
     * Set monthlyDaysOfMonth
     *
     * @param string $monthlyDaysOfMonth
     * @return Policy
     */
    public function setMonthlyDaysOfMonth(string $monthlyDaysOfMonth): static
    {
        $this->monthlyDaysOfMonth = $monthlyDaysOfMonth;

        return $this;
    }

    /**
     * Get monthlyDaysOfMonth
     *
     * @return string
     */
    public function getMonthlyDaysOfMonth(): string
    {
        return $this->monthlyDaysOfMonth;
    }

    /**
     * Set monthlyDaysOfWeek
     *
     * @param string $monthlyDaysOfWeek
     * @return Policy
     */
    public function setMonthlyDaysOfWeek(string $monthlyDaysOfWeek): static
    {
        $this->monthlyDaysOfWeek = $monthlyDaysOfWeek;

        return $this;
    }

    /**
     * Get monthlyDaysOfWeek
     *
     * @return string
     */
    public function getMonthlyDaysOfWeek(): string
    {
        return $this->monthlyDaysOfWeek;
    }

    /**
     * Set monthlyMonths
     *
     * @param string $monthlyMonths
     * @return Policy
     */
    public function setMonthlyMonths(string $monthlyMonths): static
    {
        $this->monthlyMonths = $monthlyMonths;

        return $this;
    }

    /**
     * Get monthlyMonths
     *
     * @return string
     */
    public function getMonthlyMonths(): string
    {
        return $this->monthlyMonths;
    }

    /**
     * Set monthlyCount
     *
     * @param integer $monthlyCount
     * @return Policy
     */
    public function setMonthlyCount(int $monthlyCount): static
    {
        $this->monthlyCount = $monthlyCount;

        return $this;
    }

    /**
     * Get monthlyCount
     *
     * @return integer
     */
    public function getMonthlyCount(): int
    {
        return $this->monthlyCount;
    }

    /**
     * Set yearlyHours
     *
     * @param string $yearlyHours
     * @return Policy
     */
    public function setYearlyHours(string $yearlyHours): static
    {
        $this->yearlyHours = $yearlyHours;

        return $this;
    }

    /**
     * Get yearlyHours
     *
     * @return string
     */
    public function getYearlyHours(): string
    {
        return $this->yearlyHours;
    }

    /**
     * Set yearlyDaysOfMonth
     *
     * @param string $yearlyDaysOfMonth
     * @return Policy
     */
    public function setYearlyDaysOfMonth(string $yearlyDaysOfMonth): static
    {
        $this->yearlyDaysOfMonth = $yearlyDaysOfMonth;

        return $this;
    }

    /**
     * Get yearlyDaysOfMonth
     *
     * @return string
     */
    public function getYearlyDaysOfMonth(): string
    {
        return $this->yearlyDaysOfMonth;
    }

    /**
     * Set yearlyDaysOfWeek
     *
     * @param string $yearlyDaysOfWeek
     * @return Policy
     */
    public function setYearlyDaysOfWeek(string $yearlyDaysOfWeek): static
    {
        $this->yearlyDaysOfWeek = $yearlyDaysOfWeek;

        return $this;
    }

    /**
     * Get yearlyDaysOfWeek
     *
     * @return string
     */
    public function getYearlyDaysOfWeek(): string
    {
        return $this->yearlyDaysOfWeek;
    }

    /**
     * Set yearlyMonths
     *
     * @param string $yearlyMonths
     * @return Policy
     */
    public function setYearlyMonths(string $yearlyMonths): static
    {
        $this->yearlyMonths = $yearlyMonths;

        return $this;
    }

    /**
     * Get yearlyMonths
     *
     * @return string
     */
    public function getYearlyMonths(): string
    {
        return $this->yearlyMonths;
    }

    /**
     * Set yearlyCount
     *
     * @param integer $yearlyCount
     * @return Policy
     */
    public function setYearlyCount(int $yearlyCount): static
    {
        $this->yearlyCount = $yearlyCount;

        return $this;
    }

    /**
     * Get yearlyCount
     *
     * @return integer
     */
    public function getYearlyCount(): int
    {
        return $this->yearlyCount;
    }

    /**
     * Set include
     *
     * @param string $include
     * @return Policy
     */
    public function setInclude(string $include): static
    {
        $this->include = $include;

        return $this;
    }

    /**
     * Get include
     *
     * @return string
     */
    public function getInclude(): string
    {
        return $this->include;
    }

    /**
     * Set exclude
     *
     * @param string $exclude
     * @return Policy
     */
    public function setExclude(string $exclude): static
    {
        $this->exclude = $exclude;

        return $this;
    }

    /**
     * Get exclude
     *
     * @return string
     */
    public function getExclude(): string
    {
        return $this->exclude;
    }

    /**
     * Set syncFirst
     *
     * @param boolean $syncFirst
     * @return Policy
     */
    public function setSyncFirst(bool $syncFirst): static
    {
        $this->syncFirst = $syncFirst;

        return $this;
    }

    /**
     * Get syncFirst
     *
     * @return boolean
     */
    public function getSyncFirst(): bool
    {
        return $this->syncFirst;
    }
}
