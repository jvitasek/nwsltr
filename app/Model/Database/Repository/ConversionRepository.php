<?php declare(strict_types = 1);

namespace App\Model\Database\Repository;

use App\Model\Database\Entity\Conversion;

/**
 * @method Conversion|NULL find($id, ?int $lockMode = null, ?int $lockVersion = null)
 * @method Conversion|NULL findOneBy(array $criteria, array $orderBy = null)
 * @method Conversion[] findAll()
 * @method Conversion[] findBy(array $criteria, array $orderBy = null, ?int $limit = null, ?int $offset = null)
 * @extends AbstractRepository<Conversion>
 */
class ConversionRepository extends AbstractRepository
{

}