<?php declare(strict_types = 1);

namespace App\Model\Database\Entity;

use App\Model\Database\Entity\Attributes\TCreatedAt;
use App\Model\Database\Entity\Attributes\TId;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Model\Database\Repository\CronRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Cron extends AbstractEntity
{

	use TId;
	use TCreatedAt;

	public const TYPE_SUCCESS = 1;
	public const TYPE_ERROR = 2;
	public const TYPE_INFO = 3;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	private string $title;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	private string $infoMessage;

	/**
	 * @var int
	 * @ORM\Column(type="integer")
	 */
	private int $type;

	/**
	 * @return string
	 */
	public function getTitle(): string
	{
		return $this->title;
	}

	/**
	 * @param string $title
	 */
	public function setTitle(string $title): void
	{
		$this->title = $title;
	}

	/**
	 * @return string
	 */
	public function getInfoMessage(): string
	{
		return $this->infoMessage;
	}

	/**
	 * @param string $infoMessage
	 */
	public function setInfoMessage(string $infoMessage): void
	{
		$this->infoMessage = $infoMessage;
	}

	/**
	 * @return int
	 */
	public function getType(): int
	{
		return $this->type;
	}

	/**
	 * @param int $type
	 */
	public function setType(int $type): void
	{
		$this->type = $type;
	}

}
