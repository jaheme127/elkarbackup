<?php
/**
 * @copyright 2012,2013 Binovo it Human Project, S.L.
 * @license http://www.opensource.org/licenses/bsd-license.php New-BSD
 */

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 * @ORM\Entity
 */
class User
{
    const SUPERUSER_ID = 1;
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=25, unique=true)
     */
    private string $username;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private string $salt;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private string $password;
    public string $newPassword;

    /**
     * @ORM\Column(type="string", length=60, unique=false)
     */
    private string $email;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isActive;

    /**
     * @ORM\Column(type="string")
     */
    private string $roles;

    /**
     * @ORM\Column(type="string", length=25)
     * @Assert\Language(groups={"preferences"})
     * @Assert\Choice(
     *    choices = { "en", "eu", "es", "de", "fr" },
     *    message = " a valid language"
     * )
     */
    private string $language = 'en';

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(groups={"preferences"})
     */
    private int $linesPerPage = 20;

    public function __construct()
    {
        $this->isActive = true;
        $this->salt = md5(uniqid(null, true));
    }

    public function isAccountNonExpired(): bool
    {
        return true;
    }

    public function isAccountNonLocked(): bool
    {
        return true;
    }

    public function isCredentialsNonExpired(): bool
    {
        return true;
    }

    public function isEnabled(): bool
    {
        return $this->isActive;
    }

    public function isSuperuser(): bool
    {
        return $this->id == self::SUPERUSER_ID;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getSalt(): string
    {
        return $this->salt;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getRoles(): array
    {
        return explode(',', $this->roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = implode(',', $roles);
        return $this;
    }

    public function eraseCredentials()
    {
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
     * Set salt
     *
     * @param string $salt
     * @return User
     */
    public function setSalt(string $salt): static
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return User
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
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Set language
     *
     * @param string $language
     * @return User
     */
    public function setLanguage(string $language): static
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language
     *
     * @return string
     */
    public function getLanguage(): string
    {
        return $this->language;
    }

    /**
     * Set linesPerPage
     *
     * @param integer $linesPerPage
     * @return User
     */
    public function setLinesPerPage(int $linesPerPage): static
    {
        $this->linesPerPage = $linesPerPage;

        return $this;
    }

    /**
     * Get linesPerPage
     *
     * @return integer
     */
    public function getLinesPerPage(): int
    {
        return $this->linesPerPage;
    }
}
