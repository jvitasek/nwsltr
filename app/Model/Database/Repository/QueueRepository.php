<?php declare(strict_types = 1);

namespace App\Model\Database\Repository;

use App\Model\Database\Entity\Queue;

/**
 * @method Queue|NULL find($id, ?int $lockMode = null, ?int $lockVersion = null)
 * @method Queue|NULL findOneBy(array $criteria, array $orderBy = null)
 * @method Queue[] findAll()
 * @method Queue[] findBy(array $criteria, array $orderBy = null, ?int $limit = null, ?int $offset = null)
 * @extends AbstractRepository<Queue>
 */
class QueueRepository extends AbstractRepository
{

}