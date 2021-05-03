<?php declare(strict_types = 1);

namespace App\Model\Database\Entity\Attributes;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

trait TCreatedAt
{

	/**
	 * @var DateTime
	 * @ORM\Column(type="datetime", nullable=false)
	 */
	protected DateTime $createdAt;

	public function getCreatedAt(): DateTime
	{
		return $this->createdAt;
	}

	/**
	 * @ORM\PrePersist
	 * @internal
	 */
	public function setCreatedAt(): void
	{
		$this->createdAt = new DateTime();
	}

}