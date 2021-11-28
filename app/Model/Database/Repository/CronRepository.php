<?php declare(strict_types = 1);

namespace App\Model\Database\Repository;

use App\Model\Database\Entity\Cron;

/**
 * @method Cron|NULL find($id, ?int $lockMode = null, ?int $lockVersion = null)
 * @method Cron|NULL findOneBy(array $criteria, array $orderBy = null)
 * @method Cron[] findAll()
 * @method Cron[] findBy(array $criteria, array $orderBy = null, ?int $limit = null, ?int $offset = null)
 * @extends AbstractRepository<Cron>
 */
class CronRepository extends AbstractRepository
{

	public function create(string $title, string $info, int $type)
	{
		$cron = new Cron();
		$cron->setTitle($title);
		$cron->setInfoMessage($info);
		$cron->setType($type);
		$this->getEntityManager()->persist($cron);
		$this->getEntityManager()->flush();
	}

}
