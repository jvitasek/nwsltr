<?php declare(strict_types = 1);

namespace App\Model\Database\Repository;

use App\Model\Database\Entity\Language;

/**
 * @method Language|NULL find($id, ?int $lockMode = null, ?int $lockVersion = null)
 * @method Language|NULL findOneBy(array $criteria, array $orderBy = null)
 * @method Language[] findAll()
 * @method Language[] findBy(array $criteria, array $orderBy = null, ?int $limit = null, ?int $offset = null)
 * @extends AbstractRepository<Language>
 */
class LanguageRepository extends AbstractRepository
{

}
