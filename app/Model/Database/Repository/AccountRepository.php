<?php declare(strict_types = 1);

namespace App\Model\Database\Repository;

use App\Model\Database\Entity\Account;

/**
 * @method Account|NULL find($id, ?int $lockMode = null, ?int $lockVersion = null)
 * @method Account|NULL findOneBy(array $criteria, array $orderBy = null)
 * @method Account[] findAll()
 * @method Account[] findBy(array $criteria, array $orderBy = null, ?int $limit = null, ?int $offset = null)
 * @extends AbstractRepository<Account>
 */
class AccountRepository extends AbstractRepository
{

}