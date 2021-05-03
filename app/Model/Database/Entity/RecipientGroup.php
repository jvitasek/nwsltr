<?php declare(strict_types = 1);

namespace App\Model\Database\Entity;

use App\Model\Database\Entity\Attributes\TCreatedAt;
use App\Model\Database\Entity\Attributes\TId;
use App\Model\Database\Entity\Attributes\TTitle;
use App\Model\Database\Entity\Attributes\TUpdatedAt;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Model\Database\Repository\RecipientGroupRepository")
 * @ORM\HasLifecycleCallbacks
 */
class RecipientGroup extends AbstractEntity
{

	use TId;
	use TCreatedAt;
	use TUpdatedAt;
	use TTitle;

    /**
     * @var Account
     * @ORM\ManyToOne(targetEntity="Account", inversedBy="recipientGroups")
     */
    private Account $account;

    /**
     * @var Collection
     * @ORM\ManyToMany(targetEntity="Mailing", mappedBy="recipientGroups")
     */
    private Collection $mailings;

    /**
     * @var Collection
     * @ORM\ManyToMany(targetEntity="Recipient", mappedBy="recipientGroups")
     */
    private Collection $recipients;

	public function __construct()
	{
		$this->mailings = new ArrayCollection();
		$this->recipients = new ArrayCollection();
	}

	public function getAccount(): Account
	{
		return $this->account;
	}

	public function setAccount(Account $account): void
	{
		$this->account = $account;
	}

	public function getMailings(): Collection
	{
		return $this->mailings;
	}

	public function setMailings(Collection $mailings): void
	{
		$this->mailings = $mailings;
	}

	public function getRecipients(): Collection
	{
		return $this->recipients;
	}

	public function setRecipients(Collection $recipients): void
	{
		$this->recipients = $recipients;
	}

	public function addRecipient(Recipient $recipient): void
	{
		$this->recipients[] = $recipient;
	}

	public function getSubscribedRecipients(): Collection
	{
		$result = new ArrayCollection();

		/** @var Recipient $recipient */
		foreach ($this->getRecipients() as $recipient) {
			if ($recipient->isSubscribed()) {
				if (!$result->contains($recipient)) {
					$result->add($recipient);
				}
			}
		}

		return $result;
	}

}