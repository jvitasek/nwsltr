<?php declare(strict_types = 1);

namespace App\Model\Database\Repository;

use App\Model\Database\Entity\Queue;
use App\Model\Database\Entity\Recipient;

/**
 * @method Recipient|NULL find($id, ?int $lockMode = null, ?int $lockVersion = null)
 * @method Recipient|NULL findOneBy(array $criteria, array $orderBy = null)
 * @method Recipient[] findAll()
 * @method Recipient[] findBy(array $criteria, array $orderBy = null, ?int $limit = null, ?int $offset = null)
 * @extends AbstractRepository<Recipient>
 */
class RecipientRepository extends AbstractRepository
{

	public function findByQueue(Queue $queue, bool $subscribed): array
	{
		return $this->findBy([
			'subscribed' => $subscribed,
			'email' => $queue->getEmail(),
			'account' => $queue->getMailing()->getAccount(),
		]);
	}

}