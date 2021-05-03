<?php declare(strict_types = 1);

namespace App\Model\Database\Entity;

use App\Model\Database\Entity\Attributes\TCreatedAt;
use App\Model\Database\Entity\Attributes\TFullName;
use App\Model\Database\Entity\Attributes\TId;
use App\Model\Database\Entity\Attributes\TUpdatedAt;
use App\Model\Security\Identity;
use App\Model\Security\Passwords;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;

/**
 * @ORM\Entity(repositoryClass="App\Model\Database\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks
 */
class User extends AbstractEntity
{

    use TId;
    use TCreatedAt;
    use TUpdatedAt;
    use TFullName;

	public const ROLE_ADMIN = 'admin';
	public const ROLE_USER = 'user';

	public const STATE_FRESH = 1;
	public const STATE_ACTIVATED = 2;
	public const STATE_BLOCKED = 3;

	public const STATES
		= [
			self::STATE_FRESH => 'Fresh',
			self::STATE_BLOCKED => 'Blocked',
			self::STATE_ACTIVATED => 'Active',
		];

	public const ROLES
		= [
			self::ROLE_USER => 'User',
			self::ROLE_ADMIN => 'Administrator',
		];

    /** @ORM\Column(type="string", length=255, nullable=false, unique=false) */
    private string $firstName;

    /** @ORM\Column(type="string", length=255, nullable=false, unique=false) */
    private string $lastName;

    /** @ORM\Column(type="integer", length=10, nullable=false) */
    private int $state;

    /** @ORM\Column(type="string", length=255, nullable=false, unique=true) */
    private string $email;

    /** @ORM\Column(type="string", length=255, nullable=false) */
    private ?string $password = null;

    /** @ORM\Column(type="string", length=255, nullable=false) */
    private string $role;

    /** @ORM\Column(type="datetime", nullable=true) */
    private ?DateTime $lastLoggedAt;

    /** @ORM\ManyToMany(targetEntity="Account", inversedBy="users") */
    private Collection $accounts;

    /** @ORM\OneToMany(targetEntity="Mailing", mappedBy="user") */
    private Collection $mailings;

	#[Pure] public function __construct()
	{
		$this->accounts = new ArrayCollection();
		$this->mailings = new ArrayCollection();
	}

	public function getFirstName(): string
	{
		return $this->firstName;
	}

	public function setFirstName(string $firstName): void
	{
		$this->firstName = $firstName;
	}

	public function getLastName(): string
	{
		return $this->lastName;
	}

	public function setLastName(string $lastName): void
	{
		$this->lastName = $lastName;
	}

	public function getState(): int
	{
		return $this->state;
	}

	public function setState(int $state): void
	{
		$this->state = $state;
	}

	public function getEmail(): string
	{
		return $this->email;
	}

	public function setEmail(string $email): void
	{
		$this->email = $email;
	}

	public function getPassword(): string
	{
		return $this->password;
	}

	public function setPassword(string $password): void
	{
		$passwords = new Passwords();

		if ($password && ($passwords->hash($password) !== $this->password)) {
			$this->password = $passwords->hash($password);
		}
	}

	public function getRole(): string
	{
		return $this->role;
	}

	public function setRole(string $role): void
	{
		$this->role = $role;
	}

	public function isActivated(): bool
	{
		return $this->state === self::STATE_ACTIVATED;
	}

	public function changeLoggedAt(): void
	{
		$this->lastLoggedAt = new DateTime();
	}

	public function toIdentity(): Identity
	{
		return new Identity($this->getId(), [$this->getRole()], [
			'email' => $this->getEmail(),
			'firstName' => $this->getFirstName(),
			'lastName' => $this->getLastName(),
			'state' => $this->getState(),
			'gravatar' => '//placehold.it/400x400',
		]);
	}

	public function getLastLoggedAt(): ?DateTime
	{
		return $this->lastLoggedAt;
	}

	public function setLastLoggedAt(?DateTime $lastLoggedAt): void
	{
		$this->lastLoggedAt = $lastLoggedAt;
	}

	public function getAccounts(): Collection
	{
		return $this->accounts;
	}

	public function setAccounts(Collection $accounts): void
	{
		$this->accounts = $accounts;
	}

	public function getMailings(): Collection
	{
		return $this->mailings;
	}

	public function setMailings(Collection $mailings): void
	{
		$this->mailings = $mailings;
	}

	#[Pure] public function isAdmin(): bool
	{
		return $this->getRole() === self::ROLE_ADMIN;
	}

	public function addAccount(Account $account): void
	{
		$this->accounts[] = $account;
	}

	public function getTitle(): string
	{
		return $this->getFullName();
	}

}