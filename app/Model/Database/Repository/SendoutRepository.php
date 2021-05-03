<?php declare(strict_types = 1);

namespace App\Model\Database\Repository;

use App\Model\Database\Entity\Sendout;

/**
 * @method Sendout|NULL find($id, ?int $lockMode = null, ?int $lockVersion = null)
 * @method Sendout|NULL findOneBy(array $criteria, array $orderBy = null)
 * @method Sendout[] findAll()
 * @method Sendout[] findBy(array $criteria, array $orderBy = null, ?int $limit = null, ?int $offset = null)
 * @extends AbstractRepository<Sendout>
 */
class SendoutRepository extends AbstractRepository
{

}