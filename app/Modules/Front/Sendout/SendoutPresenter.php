<?php declare(strict_types = 1);

namespace App\Modules\Front\Sendout;

use App\Model\Database\Entity\Sendout;
use App\Modules\Front\DatagridPresenter;
use Nette\Application\Attributes\Persistent;
use Nette\Application\BadRequestException;

final class SendoutPresenter extends DatagridPresenter
{

	private array $sendouts;
	private Sendout $sendout;

	public function actionList(): void
	{
		$qb = $this->initializeQueryBuilder(Sendout::class);
		$qb
			->join('p.mailing', 'm')
			->andWhere('m.account = :selectedAccount')->setParameter('selectedAccount', $this->account);
		$this->sendouts = $qb->getQuery()->getResult();
	}

	public function renderList(): void
	{
		$this->template->sendouts = $this->sendouts;
	}

	public function actionStatistics(int $id): void
	{
		/** @var Sendout|null $sendout */
		$sendout = $this->em->getRepository(Sendout::class)->find($id);

		if ($sendout !== null) {
			$this->sendout = $sendout;
		} else {
			throw new BadRequestException();
		}
	}

	public function renderStatistics(): void
	{
		$this->template->sendout = $this->sendout;
		$this->template->openedCount = $this->sendout->getCountByOpened(true);
		$this->template->notOpenedCount = $this->sendout->getCountByOpened(false);
	}

}