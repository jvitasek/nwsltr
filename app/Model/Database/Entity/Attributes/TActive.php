<?php declare(strict_types = 1);

namespace App\Model\Database\Entity\Attributes;

use Doctrine\ORM\Mapping as ORM;

trait TActive
{

	/** @ORM\Column(type="boolean", options={"default": true}) */
	protected bool $active = true;

	public function getActive(): bool
	{
		return $this->active;
	}

	public function setActive(bool $active): void
	{
		$this->active = $active;
	}

}