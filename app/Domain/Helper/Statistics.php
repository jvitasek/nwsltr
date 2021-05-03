<?php declare(strict_types = 1);

namespace App\Domain\Helper;

use App\Model\Database\Entity\Account;
use App\Model\Database\Entity\Mailing;
use App\Model\Database\Entity\Queue;
use App\Model\Database\Entity\Recipient;
use App\Model\Database\Entity\Unsubscribe;
use App\Model\Database\EntityManager;
use App\Model\Database\Repository\MailingRepository;
use DateInterval;
use DatePeriod;
use Tracy\Debugger;

final class Statistics
{

	private EntityManager $em;

	public function __construct(EntityManager $em)
	{
		$this->em = $em;
	}

	public function getTotalSent(Account $account): int
	{
		/** @var MailingRepository $mailingRepository */
		$mailingRepository = $this->em->getRepository(Mailing::class);

		return $mailingRepository
			->getByAccount($account->getId())
			->andWhere('p.status = :sentStatus')->setParameter('sentStatus', Mailing::STATUS_SENT)
			->select('COUNT(p.id)')
			->getQuery()->getSingleScalarResult();
	}

	public function getTotalOpened(Account $account): float
	{
		$totalQueueQuery = $this->em
			->getRepository(Queue::class)
			->qb()
			->select('COUNT(p.id)')
			->join('p.mailing', 'm')
			->where('IDENTITY(m.account) = :account')->setParameter('account', $account->getId());
		$openedQueueQuery = (clone $totalQueueQuery)->andWhere('p.opened = 1');
		$totalQueueCount = $totalQueueQuery->andWhere('p.sent = 1')->getQuery()->getSingleScalarResult();
		$openedQueueCount = $openedQueueQuery->getQuery()->getSingleScalarResult();

		if ($totalQueueCount > 0) {
			return round(100 * $openedQueueCount / $totalQueueCount);
		}

		return 0;
	}

	public function getTotalRecipients(Account $account): int
	{
		return $this->em
			->getRepository(Recipient::class)
			->qb()->select('COUNT(p.id)')
			->where('IDENTITY(p.account) = :accountId')->setParameter('accountId', $account->getId())
			->getQuery()->getSingleScalarResult();
	}

	public function getTotalUnsubscribes(Account $account): int
	{
		return $this->em
			->getRepository(Unsubscribe::class)
			->qb()
			->select('COUNT(p.id)')
			->join('p.mailing', 'm')
			->where('IDENTITY(m.account) = :accountId')->setParameter('accountId', $account->getId())
			->getQuery()->getSingleScalarResult();
	}

	public function getSentOpenedRationByMonths(Account $account): array
	{
		$start = (new \DateTime('now - 5 months'))->modify('first day of this month');
		$end = (new \DateTime('now'))->modify('last day of this month');
		$interval = DateInterval::createFromDateString('1 month');
		$period = new DatePeriod($start, $interval, $end);

		$months = [];

		foreach ($period as $dt) {
			$months[$dt->format('Y-m')] = [];
		}

		foreach ($months as $monthDt => $results) {
			$exploded = explode('-', $monthDt);

			if (isset($exploded[0]) && isset($exploded[1])) {
				$year = $exploded[0];
				$month = $exploded[1];

				$totalUnsubscribes = $this->em
					->getRepository(Unsubscribe::class)
					->qb()
					->select('COUNT(p.id)')
					->join('p.mailing', 'm')
					->where('IDENTITY(m.account) = :account')->setParameter('account', $account->getId())
					->andWhere('MONTH(p.createdAt) = :month')->setParameter('month', $month)
					->andWhere('YEAR(p.createdAt) = :year')->setParameter('year', $year)
					->getQuery()->getSingleScalarResult();

				$totalQueueQuery = $this->em
					->getRepository(Queue::class)
					->qb()
					->select('COUNT(p.id)')
					->join('p.mailing', 'm')
					->where('IDENTITY(m.account) = :account')->setParameter('account', $account->getId())
					->andWhere('MONTH(p.createdAt) = :month')->setParameter('month', $month)
					->andWhere('YEAR(p.createdAt) = :year')->setParameter('year', $year);

				$months[$monthDt]['totalOpened'] = (clone $totalQueueQuery)->andWhere('p.opened = 1')->getQuery()->getSingleScalarResult();
				$months[$monthDt]['totalSent'] = $totalQueueQuery->andWhere('p.sent = 1')->getQuery()->getSingleScalarResult();
				$months[$monthDt]['totalUnsubscribes'] = $totalUnsubscribes;
			}
		}

		return $months;
	}

}