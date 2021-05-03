<?php declare(strict_types = 1);

namespace App\Model\Database\Entity;

use App\Model\Database\Entity\Attributes\TCreatedAt;
use App\Model\Database\Entity\Attributes\TId;
use App\Model\Database\Entity\Attributes\TTitle;
use App\Model\Database\Entity\Attributes\TUpdatedAt;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Model\Database\Repository\ElementRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Element extends AbstractEntity
{

	use TId;
	use TCreatedAt;
	use TUpdatedAt;
	use TTitle;

    /** @ORM\Column(type="string") */
    private string $redirectToUrl;

    /** @ORM\ManyToOne(targetEntity="Mailing", inversedBy="elements") */
    private ?Mailing $mailing;

    /** @ORM\OneToMany(targetEntity="Conversion", mappedBy="element") */
    private Collection $conversions;

    /** @ORM\Column(type="integer") */
    private int $composerId;

	public function __construct()
	{
		$this->conversions = new ArrayCollection();
	}

	public function getRedirectToUrl(): string
	{
		return $this->redirectToUrl;
	}

	public function setRedirectToUrl(string $redirectToUrl): void
	{
		$this->redirectToUrl = $redirectToUrl;
	}

	public function getMailing(): ?Mailing
	{
		return $this->mailing;
	}

	public function setMailing(?Mailing $mailing): void
	{
		$this->mailing = $mailing;
	}

	/** @return ArrayCollection|Collection */
	public function getConversions(): ArrayCollection|Collection
	{
		return $this->conversions;
	}

	/** @param ArrayCollection|Collection $conversions */
	public function setConversions(ArrayCollection|Collection $conversions): void
	{
		$this->conversions = $conversions;
	}

	public function getComposerId(): int
	{
		return $this->composerId;
	}

	public function setComposerId(int $composerId): void
	{
		$this->composerId = $composerId;
	}

}