<?php declare(strict_types = 1);

namespace App\Model\Database\Entity\Attributes;

use Doctrine\ORM\Mapping as ORM;

trait TSequence
{

	/** @ORM\Column(type="integer", nullable=true, options={"default": NULL}) */
	protected ?int $sequence = null;

	public function getSequence(): ?int
	{
		return $this->sequence;
	}

	public function setSequence(?int $sequence): void
	{
		$this->sequence = $sequence;
	}

}