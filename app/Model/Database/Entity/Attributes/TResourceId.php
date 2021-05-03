<?php declare(strict_types = 1);

namespace App\Model\Database\Entity\Attributes;

trait TResourceId
{

	function getResourceId(): string
	{
		return self::class;
	}

}
