<?php declare(strict_types = 1);

namespace App\Model\Database\Entity;

use App\Model\Database\Entity\Attributes\TActive;
use App\Model\Database\Entity\Attributes\TCreatedAt;
use App\Model\Database\Entity\Attributes\TId;
use App\Model\Database\Entity\Attributes\TTitle;
use App\Model\Database\Entity\Attributes\TUpdatedAt;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Model\Database\Repository\AccountRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Account extends AbstractEntity
{

	use TId;
	use TCreatedAt;
	use TUpdatedAt;
	use TTitle;
	use TActive;

	/** @ORM\Column(type="string") */
	private string $emailFrom;

	/** @ORM\Column(type="string", nullable=true, options={"default": NULL}) */
	private ?string $emailFromTitle;

	/** @ORM\Column(type="string", nullable=true, options={"default": NULL}) */
	private ?string $emailReplyTo;

	/** @ORM\Column(type="string", nullable=true, options={"default": NULL}) */
	private ?string $websiteUrl;

	/** @ORM\Column(type="string", nullable=true, options={"default": NULL}) */
	private ?string $unsubscribeRedirectUrl;

	/** @ORM\Column(type="boolean", options={"default": true}) */
	private bool $showUnsubscribeFeedbackForm = true;

	/** @ORM\Column(type="boolean", options={"default": true}) */
	private bool $showResubscribeButton = true;

	/** @ORM\Column(type="string", nullable=true, length=6, options={"fixed": true, "default": NULL}) */
	private ?string $primaryColorHex;

	/** @ORM\Column(type="string", nullable=true, length=6, options={"fixed": true, "default": NULL}) */
	private ?string $secondaryColorHex;

	/** @ORM\Column(type="string", nullable=true, options={"default": NULL}) */
	private ?string $logo;

	/** @ORM\Column(type="string", nullable=true, options={"default": NULL}) */
	private ?string $smtpHost;

	/** @ORM\Column(type="string", nullable=true, options={"default": NULL}) */
	private ?string $smtpUsername;

	/** @ORM\Column(type="string", nullable=true, options={"default": NULL}) */
	private ?string $smtpPassword;

	/** @ORM\Column(type="string", nullable=true, options={"default": NULL}) */
	private ?string $smtpSecure;

	/** @ORM\Column(type="string", nullable=true, options={"default": NULL}) */
	private ?string $smtpPort;

	/** @ORM\ManyToMany(targetEntity="User", mappedBy="accounts") */
	private Collection $users;

	/** @ORM\OneToMany(targetEntity="RecipientGroup", mappedBy="account") */
	private Collection $recipientGroups;

	/** @ORM\OneToMany(targetEntity="Recipient", mappedBy="account") */
	private Collection $recipients;

	/** @ORM\OneToMany(targetEntity="Mailing", mappedBy="account") */
	private Collection $mailings;

	public function __construct()
	{
		$this->users = new ArrayCollection();
		$this->recipients = new ArrayCollection();
		$this->recipientGroups = new ArrayCollection();
		$this->mailings = new ArrayCollection();
	}

	public function getEmailFrom(): string
	{
		return $this->emailFrom;
	}

	public function setEmailFrom(string $emailFrom): void
	{
		$this->emailFrom = $emailFrom;
	}

	public function getEmailFromTitle(): ?string
	{
		return $this->emailFromTitle;
	}

	public function setEmailFromTitle(?string $emailFromTitle): void
	{
		$this->emailFromTitle = $emailFromTitle;
	}

	public function getEmailReplyTo(): ?string
	{
		return $this->emailReplyTo;
	}

	public function setEmailReplyTo(?string $emailReplyTo): void
	{
		$this->emailReplyTo = $emailReplyTo;
	}

	public function getWebsiteUrl(): ?string
	{
		return $this->websiteUrl;
	}

	public function setWebsiteUrl(?string $websiteUrl): void
	{
		$this->websiteUrl = $websiteUrl;
	}

	public function getUnsubscribeRedirectUrl(): ?string
	{
		return $this->unsubscribeRedirectUrl;
	}

	public function setUnsubscribeRedirectUrl(?string $unsubscribeRedirectUrl): void
	{
		$this->unsubscribeRedirectUrl = $unsubscribeRedirectUrl;
	}

	public function getPrimaryColorHex(): ?string
	{
		return $this->primaryColorHex;
	}

	public function setPrimaryColorHex(?string $primaryColorHex): void
	{
		$this->primaryColorHex = $primaryColorHex;
	}

	public function getSecondaryColorHex(): ?string
	{
		return $this->secondaryColorHex;
	}

	public function setSecondaryColorHex(?string $secondaryColorHex): void
	{
		$this->secondaryColorHex = $secondaryColorHex;
	}

	public function getLogo(): ?string
	{
		return $this->logo;
	}

	public function setLogo(?string $logo): void
	{
		$this->logo = $logo;
	}

	public function getSmtpHost(): ?string
	{
		return $this->smtpHost;
	}

	public function setSmtpHost(?string $smtpHost): void
	{
		$this->smtpHost = $smtpHost;
	}

	public function getSmtpUsername(): ?string
	{
		return $this->smtpUsername;
	}

	public function setSmtpUsername(?string $smtpUsername): void
	{
		$this->smtpUsername = $smtpUsername;
	}

	public function getSmtpPassword(): ?string
	{
		return $this->smtpPassword;
	}

	public function setSmtpPassword(?string $smtpPassword): void
	{
		$this->smtpPassword = $smtpPassword;
	}

	public function getSmtpSecure(): ?string
	{
		return $this->smtpSecure;
	}

	public function setSmtpSecure(?string $smtpSecure): void
	{
		$this->smtpSecure = $smtpSecure;
	}

	public function getUsers(): Collection
	{
		return $this->users;
	}

	public function setUsers(Collection $users): void
	{
		$this->users = $users;
	}

	public function getRecipientGroups(): Collection
	{
		return $this->recipientGroups;
	}

	public function setRecipientGroups(Collection $recipientGroups): void
	{
		$this->recipientGroups = $recipientGroups;
	}

	public function getMailings(): Collection
	{
		return $this->mailings;
	}

	public function setMailings(Collection $mailings): void
	{
		$this->mailings = $mailings;
	}

	public function isShowUnsubscribeFeedbackForm(): bool
	{
		return $this->showUnsubscribeFeedbackForm;
	}

	public function setShowUnsubscribeFeedbackForm(bool $showUnsubscribeFeedbackForm): void
	{
		$this->showUnsubscribeFeedbackForm = $showUnsubscribeFeedbackForm;
	}

	public function isShowResubscribeButton(): bool
	{
		return $this->showResubscribeButton;
	}

	public function setShowResubscribeButton(bool $showResubscribeButton): void
	{
		$this->showResubscribeButton = $showResubscribeButton;
	}

	public function getSmtpPort(): ?string
	{
		return $this->smtpPort;
	}

	public function setSmtpPort(?string $smtpPort): void
	{
		$this->smtpPort = $smtpPort;
	}

	public function getRecipients(): ArrayCollection|Collection
	{
		return $this->recipients;
	}

	public function setRecipients(ArrayCollection|Collection $recipients): void
	{
		$this->recipients = $recipients;
	}

}