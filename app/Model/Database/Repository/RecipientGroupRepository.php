<?php declare(strict_types = 1);

namespace App\Model\Database\Repository;

use App\Model\Database\Entity\RecipientGroup;

/**
 * @method RecipientGroup|NULL find($id, ?int $lockMode = null, ?int $lockVersion = null)
 * @method RecipientGroup|NULL findOneBy(array $criteria, array $orderBy = null)
 * @method RecipientGroup[] findAll()
 * @method RecipientGroup[] findBy(array $criteria, array $orderBy = null, ?int $limit = null, ?int $offset = null)
 * @extends AbstractRepository<RecipientGroup>
 */
class RecipientGroupRepository extends AbstractRepository
{

}