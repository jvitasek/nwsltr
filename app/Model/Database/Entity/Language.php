<?php declare(strict_types = 1);

namespace App\Model\Database\Entity;

use App\Model\Database\Entity\Attributes\TCreatedAt;
use App\Model\Database\Entity\Attributes\TId;
use App\Model\Database\Entity\Attributes\TSequence;
use App\Model\Database\Entity\Attributes\TTitle;
use App\Model\Database\Entity\Attributes\TUpdatedAt;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Model\Database\Repository\LanguageRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Language extends AbstractEntity
{

	use TId;
	use TCreatedAt;
	use TUpdatedAt;
	use TTitle;
	use TSequence;

	/** @ORM\Column(type="string") */
	private string $code;

	/**
	 * @return string
	 */
	public function getCode(): string
	{
		return $this->code;
	}

	/**
	 * @param string $code
	 */
	public function setCode(string $code): void
	{
		$this->code = $code;
	}

}
