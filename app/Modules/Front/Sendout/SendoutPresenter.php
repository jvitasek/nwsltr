<?php declare(strict_types = 1);

namespace App\Modules\Front\Sendout;

use App\Model\Database\Entity\Queue;
use App\Model\Database\Entity\Sendout;
use App\Modules\Front\DatagridPresenter;
use Nette\Application\BadRequestException;

final class SendoutPresenter extends DatagridPresenter
{

	private array $sendouts;
	private array $queueItems;
	private Sendout $sendout;

	public function actionList(): void
	{
		$qb = $this->initializeQueryBuilder(Sendout::class);
		$qb
			->join('p.mailing', 'm')
			->andWhere('m.account = :selectedAccount')->setParameter('selectedAccount', $this->account);

		$paginator = $this['pagination']->getPaginator();
		$paginator->itemsPerPage = self::DEFAULT_ITEMS_PER_PAGE;
		$paginator->itemCount = count($qb->getQuery()->getResult());
		$qb->setMaxResults($paginator->itemsPerPage)->setFirstResult($paginator->offset);

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
			$qb = $this->initializeQueryBuilder(Queue::class);
			$qb = $qb
				->andWhere('p.sendout = :sendout')
				->setParameter('sendout', $sendout);

			$paginator = $this['pagination']->getPaginator();
			$paginator->itemsPerPage = self::DEFAULT_ITEMS_PER_PAGE;
			$paginator->itemCount = count($qb->getQuery()->getResult());
			$qb->setMaxResults($paginator->itemsPerPage)->setFirstResult($paginator->offset);

			$this->queueItems = $qb->getQuery()->getResult();
		} else {
			throw new BadRequestException();
		}
	}

	public function renderStatistics(): void
	{
		$this->template->sendout = $this->sendout;
		$this->template->queueItems = $this->queueItems;
		$this->template->openedCount = $this->sendout->getCountByOpened(true);
		$this->template->notOpenedCount = $this->sendout->getCountByOpened(false);
	}

}
