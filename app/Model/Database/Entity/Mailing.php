<?php declare(strict_types = 1);

namespace App\Model\Database\Entity;

use App\Model\Database\Entity\Attributes\TCreatedAt;
use App\Model\Database\Entity\Attributes\TId;
use App\Model\Database\Entity\Attributes\TTitle;
use App\Model\Database\Entity\Attributes\TUpdatedAt;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Latte\Engine;
use Nette\Application\LinkGenerator;
use Nette\Mail\Mailer;
use Nette\Mail\Message;
use Nette\Mail\SmtpException;
use Nette\Mail\SmtpMailer;
use Tracy\Debugger;

/**
 * @ORM\Entity(repositoryClass="App\Model\Database\Repository\MailingRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Mailing extends AbstractEntity
{

	use TId;
	use TCreatedAt;
	use TUpdatedAt;
	use TTitle;

	public const THROTTLE_MICROSECONDS = 500000;

	public const STATUS_CONCEPT = 1;
	public const STATUS_READY = 2;
	public const STATUS_SENDING = 3;
	public const STATUS_SENT = 4;

	public const STATES
		= [
			self::STATUS_CONCEPT => 'Concept',
			self::STATUS_READY => 'Ready',
			self::STATUS_SENDING => 'Sending',
			self::STATUS_SENT => 'Sent',
		];

	public const STATES_RETURNABLE
		= [
			self::STATUS_CONCEPT, self::STATUS_READY,
		];

	public const DEFAULT_BACKGROUND_COLOR_HEX = '129ED6';
	public const DEFAULT_TEXT_COLOR_HEX = 'FFFFFF';

	/** @ORM\Column(type="string", nullable=true, options={"default": NULL}) */
	protected ?string $subject = '';

	/** @ORM\Column(type="string", nullable=true, options={"default": NULL}) */
	protected ?string $emailFrom = '';

	/** @ORM\Column(type="datetime", nullable=true, options={"default": NULL}) */
	protected ?DateTime $sendDate;

	/** @ORM\Column(type="text", nullable=true, options={"default": NULL}) */
	private ?string $jsonData = '';

	/** @ORM\ManyToOne(targetEntity="User", inversedBy="mailings") */
	private User $user;

	/** @ORM\ManyToOne(targetEntity="Account", inversedBy="mailings") */
	private Account $account;

	/** @ORM\Column(type="smallint", options={"default": 1}) */
	private int $status = 1;

	/** @ORM\Column(type="string", nullable=true, options={"default": NULL}) */
	private ?string $apiCode = '';

	/** @ORM\OneToMany(targetEntity="Element", mappedBy="mailing", cascade={"remove"}) */
	private Collection $elements;

	/** @ORM\OneToMany(targetEntity="Queue", mappedBy="mailing", cascade={"remove"}) */
	private Collection $queueItems;

	/** @ORM\OneToMany(targetEntity="Sendout", mappedBy="mailing", cascade={"remove"}) */
	private Collection $sendouts;

	/** @ORM\ManyToMany(targetEntity="RecipientGroup", inversedBy="mailings") */
	private Collection $recipientGroups;

	/** @ORM\OneToMany(targetEntity="Unsubscribe", mappedBy="mailing", cascade={"remove"}) */
	private Collection $unsubscribes;

	#[Pure] public function __construct()
	{
		$this->elements = new ArrayCollection();
		$this->queueItems = new ArrayCollection();
		$this->sendouts = new ArrayCollection();
		$this->recipientGroups = new ArrayCollection();
		$this->unsubscribes = new ArrayCollection();
	}

	public function getSendDate(): ?DateTime
	{
		return $this->sendDate;
	}

	public function setSendDate(?DateTime $sendDate): void
	{
		$this->sendDate = $sendDate;
	}

	public function getUser(): User
	{
		return $this->user;
	}

	public function setUser(User $user): void
	{
		$this->user = $user;
	}

	public function getAccount(): Account
	{
		return $this->account;
	}

	public function setAccount(Account $account): void
	{
		$this->account = $account;
	}

	public function isConcept(): bool
	{
		return $this->status === self::STATUS_CONCEPT;
	}

	public function isReady(): bool
	{
		return $this->status === self::STATUS_READY;
	}

	public function isSending(): bool
	{
		return $this->status === self::STATUS_SENDING;
	}

	public function isSent(): bool
	{
		return $this->status === self::STATUS_SENT;
	}

	public function getApiCode(): ?string
	{
		return $this->apiCode;
	}

	public function setApiCode(?string $apiCode): void
	{
		$this->apiCode = $apiCode;
	}

	/** @return Collection */
	public function getElements(): ArrayCollection|Collection
	{
		return $this->elements;
	}

	/** @param ArrayCollection|Collection $elements */
	public function setElements(ArrayCollection|Collection $elements): void
	{
		$this->elements = $elements;
	}

	/** @return ArrayCollection|Collection */
	public function getQueueItems(): ArrayCollection|Collection
	{
		return $this->queueItems;
	}

	/** @param ArrayCollection|Collection $queueItems */
	public function setQueueItems(ArrayCollection|Collection $queueItems): void
	{
		$this->queueItems = $queueItems;
	}

	/** @return ArrayCollection|Collection */
	public function getSendouts(): ArrayCollection|Collection
	{
		return $this->sendouts;
	}

	/** @param ArrayCollection|Collection $sendouts */
	public function setSendouts(ArrayCollection|Collection $sendouts): void
	{
		$this->sendouts = $sendouts;
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

	/** @return ArrayCollection|Collection */
	public function getUnsubscribes(): ArrayCollection|Collection
	{
		return $this->unsubscribes;
	}

	/** @param ArrayCollection|Collection $unsubscribes */
	public function setUnsubscribes(ArrayCollection|Collection $unsubscribes): void
	{
		$this->unsubscribes = $unsubscribes;
	}

	public function getJsonData(): ?string
	{
		return $this->jsonData;
	}

	public function setJsonData(?string $jsonData): void
	{
		$this->jsonData = $jsonData;
	}

	public function getSubject(): ?string
	{
		return $this->subject;
	}

	public function setSubject(?string $subject): void
	{
		$this->subject = $subject;
	}

	public function getJsonFormatted(): array
	{
		if ($this->getJsonData()) {
			return json_decode($this->getJsonData(), true);
		}

		return [];
	}

	public function getHtml(LinkGenerator $linkGenerator, ?string $queueHash = null): string
	{
		$result = '';
		$componentLatte = '';
		$latteEngine = new Engine();

		$components = $this->getJsonFormatted();

		if ($components && isset($components['body'])) {
			$unsubscribeLink = '#';

			if ($queueHash) {
				$unsubscribeLink = $linkGenerator->link('Api:Unsubscribe:read', ['queueHash' => $queueHash, 'mailingId' => $this->getId()]);
			}

			$backToEditLink = '';
			$backToMailingsLink = '';

			if (!$queueHash) {
				$backToEditLink = $linkGenerator->link('Front:Editor:default', ['id' => $this->getId()]);
				$backToMailingsLink = $linkGenerator->link('Front:Mailing:list');
			}

			$domain = rtrim($linkGenerator->link('Front:Home:default'), '/');

			$result .= $latteEngine->renderToString(UI_DIR . '/Components/@layout.latte', [
				'mailing' => $this,
				'unsubscribeLink' => $unsubscribeLink,
				'preview' => ($queueHash !== null),
				'backToEditLink' => $backToEditLink,
				'backToMailingsLink' => $backToMailingsLink,
				'domain' => $domain,
			]);

			foreach ($components['body'] as $component) {
				$templatePath = UI_DIR . '/Components/' . $component['component'] . '.latte';

				if (file_exists($templatePath)) {
					$html = $latteEngine->renderToString($templatePath, [
						'data' => $component,
						'mailing' => $this,
						'domain' => $domain,
					]);

					if ($component['component'] === 'Button') {
						if ($queueHash) {
							$buttonConversionLink = $linkGenerator->link('Api:Conversion:read', ['queueHash' => $queueHash, 'elementId' => $component['id']]);
							$html = str_replace('[[LINK_TO_REPLACE]]', $buttonConversionLink, $html);
						} else {
							$html = str_replace('[[LINK_TO_REPLACE]]', $component['link'], $html);
						}
					}

					$componentLatte .= $html;
				}
			}
		}

		$result = str_replace('[[COMPONENTS]]', $componentLatte, $result);

		// this is the way we can tell
		// if the user has opened the e-mail

		if ($queueHash) {
			$trackingLink = $linkGenerator->link('Api:Queue:read', ['queueHash' => $queueHash, 'mailingId' => $this->getId()]);
			$result .= '<img alt="" src="' . $trackingLink . '" width="1" height="1" border="0" />';
		}

		return $result;
	}

	public function getStatus(): int
	{
		return $this->status;
	}

	public function setStatus(int $status): void
	{
		$this->status = $status;
	}

	public function getStatusTitle(): string
	{
		return self::STATES[$this->getStatus()];
	}

	public function getTotalNumberOfRecipients(): int
	{
		$total = 0;

		foreach ($this->getRecipientGroups() as $recipientGroup) {
			$total += $recipientGroup->getSubscribedRecipients()->count();
		}

		return $total;
	}

	public function getSubscribedRecipients(): ArrayCollection
	{
		$result = new ArrayCollection();

		foreach ($this->getRecipientGroups() as $recipientGroup) {
			foreach ($recipientGroup->getSubscribedRecipients() as $recipient) {
				if (!$result->contains($recipient)) {
					$result->add($recipient);
				}
			}
		}

		return $result;
	}

	/** @return bool|string */
	public function isOkToBeSent(): bool|string
	{
		if ($this->getStatus() !== Mailing::STATUS_READY) {
			return 'Mailing ' . $this->getId() . ' failed: not ready anymore';
		}

		// we cannot send, no groups set
		if ($this->getRecipientGroups()->count() === 0) {
			return 'Mailing ' . $this->getId() . ' failed: no recipient groups set';
		}

		// we cannot send, no valid recipients
		if ($this->getTotalNumberOfRecipients() === 0) {
			return 'Mailing ' . $this->getId() . ' failed: no subscribed recipients';
		}

		if (!$this->getSubject()) {
			return 'Mailing ' . $this->getId() . ' failed: no subject';
		}

		return true;
	}

	public function isOkToBeTestSent(): bool
	{
		return $this->getSubject() !== null && $this->getSubject() !== '';
	}

	public function addRecipientGroup(RecipientGroup $recipientGroup): void
	{
		$this->recipientGroups[] = $recipientGroup;
	}

	public function isFilled(): bool
	{
		if ($this->getTitle() || $this->getSubject()) {
			return true;
		}

		$json = $this->getJsonFormatted();

		return !empty($json['body']);
	}

	public function send(Mailer $mailer, LinkGenerator $linkGenerator, string $email, ?string $queueHash = null): bool
	{
		$account = $this->getAccount();
		$mail = new Message();

		if ($this->getApiCode()) {
			$mail->setHeader('X-CampaignId', $this->getApiCode());
		}

		$mail->setFrom($this->getEmailFrom() ?: $account->getEmailFrom(), $account->getEmailFromTitle());

		if ($account->getEmailReplyTo()) {
			$mail->addReplyTo($account->getEmailReplyTo());
		}

		$mail->addTo($email);
		$mail->setSubject($this->getSubject());
		$mail->setHtmlBody($this->getHtml($linkGenerator, $queueHash));

		try {
			$mailer->send($mail);

			return true;
		} catch (SmtpException $e) {
			Debugger::log($e->getMessage(), 'smtp');
		}

		return false;
	}

	public function getEmailFrom(): ?string
	{
		return $this->emailFrom;
	}

	public function setEmailFrom(?string $emailFrom): void
	{
		$this->emailFrom = $emailFrom;
	}

}
