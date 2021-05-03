<?php declare(strict_types = 1);

namespace App\Model\Database\Entity;

use App\Model\Database\Entity\Attributes\TCreatedAt;
use App\Model\Database\Entity\Attributes\TId;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Model\Database\Repository\ConversionRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Conversion extends AbstractEntity
{

	use TId;
	use TCreatedAt;

	/** @ORM\ManyToOne(targetEntity="Queue") */
	private Queue $queue;

	/** @ORM\ManyToOne(targetEntity="Element", inversedBy="conversions") */
	private Element $element;

	public function getElement(): Element
	{
		return $this->element;
	}

	public function setElement(Element $element): void
	{
		$this->element = $element;
	}

	public function getQueue(): Queue
	{
		return $this->queue;
	}

	public function setQueue(Queue $queue): void
	{
		$this->queue = $queue;
	}

}