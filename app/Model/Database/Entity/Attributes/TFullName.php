<?php declare(strict_types = 1);

namespace App\Model\Database\Entity\Attributes;

trait TFullName
{

	public function getFullName(): string
	{
		$result = '';

		if (method_exists($this, 'getFirstName')) {
			$result .= $this->getFirstName();
		}

		$result .= ' ';

		if (method_exists($this, 'getLastName')) {
			$result .= $this->getLastName();
		}

		return $result;
	}

}
