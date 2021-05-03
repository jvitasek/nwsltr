<?php declare(strict_types = 1);

namespace App\Model\Database;

use App\Model\Database\Repository\AbstractRepository;
use Nettrine\ORM\EntityManagerDecorator;

class EntityManager extends EntityManagerDecorator
{

	use TRepositories;

	public function getRepository($entityName): AbstractRepository
	{
		return parent::getRepository($entityName);
	}

}