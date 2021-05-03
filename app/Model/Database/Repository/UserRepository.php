<?php declare(strict_types = 1);

namespace App\Model\Database\Repository;

use App\Model\Database\Entity\User;

/**
 * @method User|NULL find($id, ?int $lockMode = null, ?int $lockVersion = null)
 * @method User|NULL findOneBy(array $criteria, array $orderBy = null)
 * @method User[] findAll()
 * @method User[] findBy(array $criteria, array $orderBy = null, ?int $limit = null, ?int $offset = null)
 * @extends AbstractRepository<User>
 */
class UserRepository extends AbstractRepository
{

	public function findOneByEmail(string $email): ?User
	{
		return $this->findOneBy(['email' => $email]);
	}

}