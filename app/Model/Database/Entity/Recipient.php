<?php declare(strict_types = 1);

namespace App\Model\Database\Entity;

use App\Model\Database\Entity\Attributes\TCreatedAt;
use App\Model\Database\Entity\Attributes\TFullName;
use App\Model\Database\Entity\Attributes\TId;
use App\Model\Database\Entity\Attributes\TUpdatedAt;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;

/**
 * @ORM\Entity(repositoryClass="App\Model\Database\Repository\RecipientRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Recipient extends AbstractEntity
{

	use TId;
	use TCreatedAt;
	use TUpdatedAt;
	use TFullName;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private string $email;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true, options={"default": NULL})
     */
    private ?string $firstName;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true, options={"default": NULL})
     */
    private ?string $lastName;

    /**
     * @var bool
     * @ORM\Column(type="boolean", options={"default": true})
     */
    private bool $subscribed = true;

    /**
     * @var Account
     * @ORM\ManyToOne(targetEntity="Account", inversedBy="recipients")
     */
    private Account $account;

    /**
     * @var Collection
     * @ORM\ManyToMany(targetEntity="RecipientGroup", inversedBy="recipients")
     */
    private Collection $recipientGroups;

	#[Pure] public function __construct()
	{
		$this->recipientGroups = new ArrayCollection();
	}

	#[Pure] public function getTitle(): string
	{
		return $this->getEmail();
	}

	public function getEmail(): string
	{
		return $this->email;
	}

	public function setEmail(string $email): void
	{
		$this->email = $email;
	}

	public function getFirstName(): ?string
	{
		return $this->firstName;
	}

	public function setFirstName(?string $firstName): void
	{
		$this->firstName = $firstName;
	}

	public function getLastName(): ?string
	{
		return $this->lastName;
	}

	public function setLastName(?string $lastName): void
	{
		$this->lastName = $lastName;
	}

	public function isSubscribed(): bool
	{
		return $this->subscribed;
	}

	public function setSubscribed(bool $subscribed): void
	{
		$this->subscribed = $subscribed;
	}

	public function getAccount(): Account
	{
		return $this->account;
	}

	public function setAccount(Account $account): void
	{
		$this->account = $account;
	}

	/** @return ArrayCollection|Collection */
	public function getRecipientGroups(): ArrayCollection|Collection
	{
		return $this->recipientGroups;
	}

	/** @param ArrayCollection|Collection $recipientGroups */
	public function setRecipientGroups(ArrayCollection|Collection $recipientGroups): void
	{
		$this->recipientGroups = $recipientGroups;
	}

	public function addRecipientGroup(RecipientGroup $recipientGroup): void
	{
		$this->recipientGroups[] = $recipientGroup;
	}

}