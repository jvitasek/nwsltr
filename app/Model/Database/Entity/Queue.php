<?php declare(strict_types = 1);

namespace App\Model\Database\Entity;

use App\Model\Database\Entity\Attributes\TCreatedAt;
use App\Model\Database\Entity\Attributes\TId;
use App\Model\Database\Entity\Attributes\TUpdatedAt;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Nette\Application\LinkGenerator;
use Nette\Mail\Mailer;
use Nette\Mail\Message;
use Nette\Mail\SmtpException;
use Nette\Mail\SmtpMailer;
use Nette\Utils\Random;

/**
 * @ORM\Entity(repositoryClass="App\Model\Database\Repository\QueueRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(indexes={@ORM\Index(name="hash_idx", columns={"hash"})})
 */
class Queue extends AbstractEntity
{

	use TId;
	use TCreatedAt;
	use TUpdatedAt;

	/**
	 * @var DateTime|null
	 * @ORM\Column(type="datetime", nullable=true, options={"default": NULL})
	 */
	private ?DateTime $timeSent;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	private string $email;

	/**
	 * @var Mailing
	 * @ORM\ManyToOne(targetEntity="Mailing", inversedBy="queueItems")
	 */
	private Mailing $mailing;

	/**
	 * @var Sendout
	 * @ORM\ManyToOne(targetEntity="Sendout", inversedBy="queueItems")
	 */
	private Sendout $sendout;

	/**
	 * @var bool
	 * @ORM\Column(type="boolean", options={"default": false})
	 */
	private bool $sent = false;

	/**
	 * @var bool
	 * @ORM\Column(type="boolean", options={"default": false})
	 */
	private bool $opened = false;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	private string $hash;

	public function getTimeSent(): ?DateTime
	{
		return $this->timeSent;
	}

	public function setTimeSent(?DateTime $timeSent): void
	{
		$this->timeSent = $timeSent;
	}

	public function getEmail(): string
	{
		return $this->email;
	}

	public function setEmail(string $email): void
	{
		$this->email = $email;
	}

	public function getMailing(): Mailing
	{
		return $this->mailing;
	}

	public function setMailing(Mailing $mailing): void
	{
		$this->mailing = $mailing;
	}

	public function isSent(): bool
	{
		return $this->sent;
	}

	public function setSent(bool $sent): void
	{
		$this->sent = $sent;
	}

	public function isOpened(): bool
	{
		return $this->opened;
	}

	public function setOpened(bool $opened): void
	{
		$this->opened = $opened;
	}

	public function getHash(): string
	{
		return $this->hash;
	}

	/**
	 * @ORM\PrePersist
	 * @internal
	 */
	public function setHash(): void
	{
		$this->hash = sha1(Random::generate(5) . $this->getId());
	}

	public function getSendout(): Sendout
	{
		return $this->sendout;
	}

	public function setSendout(Sendout $sendout): void
	{
		$this->sendout = $sendout;
	}

	public function getOpenedStatus(): string
	{
		if ($this->isOpened()) {
			return 'Opened';
		} else {
			return 'Not Opened';
		}
	}

}