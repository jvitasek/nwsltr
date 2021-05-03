<?php declare(strict_types = 1);

namespace App\Model\Database\Entity\Attributes;

use Doctrine\ORM\Mapping as ORM;

trait TId
{

	/**
	 * @ORM\Column(type="integer", nullable=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 */
	private ?int $id = null;

	public function getId(): ?int
	{
		return $this->id;
	}

	public function __clone()
	{
		$this->id = null;
	}

}