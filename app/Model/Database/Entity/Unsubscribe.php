<?php declare(strict_types = 1);

namespace App\Model\Database\Entity;

use App\Model\Database\Entity\Attributes\TCreatedAt;
use App\Model\Database\Entity\Attributes\TId;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Model\Database\Repository\UnsubscribeRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Unsubscribe extends AbstractEntity
{

	use TId;
	use TCreatedAt;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	private string $email;

	/**
	 * @var string|null
	 * @ORM\Column(type="text", nullable=true, options={"default": NULL})
	 */
	private ?string $note;

	/**
	 * @var Mailing
	 * @ORM\ManyToOne(targetEntity="Mailing", inversedBy="unsubscribes")
	 */
	private Mailing $mailing;

	/** @ORM\Column(type="boolean", options={"default": false}) */
	private bool $resubscribed = false;

	public function getEmail(): string
	{
		return $this->email;
	}

	public function setEmail(string $email): void
	{
		$this->email = $email;
	}

	public function getNote(): ?string
	{
		return $this->note;
	}

	public function setNote(?string $note): void
	{
		$this->note = $note;
	}

	public function getMailing(): Mailing
	{
		return $this->mailing;
	}

	public function setMailing(Mailing $mailing): void
	{
		$this->mailing = $mailing;
	}

	public function isResubscribed(): bool
	{
		return $this->resubscribed;
	}

	public function setResubscribed(bool $resubscribed): void
	{
		$this->resubscribed = $resubscribed;
	}

}