<?php declare(strict_types = 1);

namespace App\Model\Database\Entity;

use App\Model\Database\Entity\Attributes\TCreatedAt;
use App\Model\Database\Entity\Attributes\TId;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;

/**
 * @ORM\Entity(repositoryClass="App\Model\Database\Repository\SendoutRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Sendout extends AbstractEntity
{

	use TId;
	use TCreatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="Mailing", inversedBy="sendouts")
     */
    private Mailing $mailing;

    /**
     * @ORM\Column(type="datetime", nullable=true, options={"default": NULL})
     */
    private ?DateTime $finishedSendingAt;

    /**
     * @ORM\Column(type="string", nullable=true, options={"default": NULL})
     */
    private ?string $logMessage;

    /**
     * @ORM\OneToMany(targetEntity="Queue", mappedBy="sendout")
     */
    private Collection $queueItems;

	#[Pure] public function __construct()
	{
		$this->queueItems = new ArrayCollection();
	}

	public function getMailing(): Mailing
	{
		return $this->mailing;
	}

	public function setMailing(Mailing $mailing): void
	{
		$this->mailing = $mailing;
	}

	public function getFinishedSendingAt(): ?DateTime
	{
		return $this->finishedSendingAt;
	}

	public function setFinishedSendingAt(?DateTime $finishedSendingAt): void
	{
		$this->finishedSendingAt = $finishedSendingAt;
	}

	public function getDuration(): string
	{
		if ($this->getFinishedSendingAt()) {
			$difference = $this->getFinishedSendingAt()->diff($this->getCreatedAt());

			if ($difference->h !== 0) {
				return $difference->h . ' hours ' . $difference->i . ' minutes ' . $difference->s . ' seconds';
			} elseif ($difference->i !== 0) {
				return $difference->i . ' minutes ' . $difference->s . ' seconds';
			} elseif ($difference->s !== 0) {
				return $difference->s . ' seconds';
			}
		}
		return 'Running';
	}

	public function getQueueItems(): Collection
	{
		return $this->queueItems;
	}

	public function setQueueItems(Collection $queueItems): void
	{
		$this->queueItems = $queueItems;
	}

	public function getCountByOpened(bool $countOpened): int
	{
		$total = 0;

		/** @var Queue $queueItem */
		foreach ($this->getQueueItems() as $queueItem) {
			if ($queueItem->isOpened() === $countOpened) {
				$total += 1;
			}
		}

		return $total;
	}

	public function getLogMessage(): ?string
	{
		return $this->logMessage;
	}

	public function setLogMessage(string $logMessage): void
	{
		$this->logMessage = $logMessage;
	}

}
