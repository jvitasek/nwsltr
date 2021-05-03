<?php declare(strict_types = 1);

namespace App\Model\Database\Repository;

use App\Model\Database\Entity\Mailing;
use Doctrine\ORM\QueryBuilder;

/**
 * @method Mailing|NULL find($id, ?int $lockMode = null, ?int $lockVersion = null)
 * @method Mailing|NULL findOneBy(array $criteria, array $orderBy = null)
 * @method Mailing[] findAll()
 * @method Mailing[] findBy(array $criteria, array $orderBy = null, ?int $limit = null, ?int $offset = null)
 * @extends AbstractRepository<Mailing>
 */
class MailingRepository extends AbstractRepository
{

	public function getByAccount(int $accountId): QueryBuilder
	{
		return $this->qb()
			->where('IDENTITY(p.account) = :accountId')
			->setParameter('accountId', $accountId);
	}

}