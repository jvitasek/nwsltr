<?php declare(strict_types = 1);

namespace App\Model\Database\Repository;

use App\Model\Database\Entity\Element;
use App\Model\Database\Entity\Mailing;

/**
 * @method Element|NULL find($id, ?int $lockMode = null, ?int $lockVersion = null)
 * @method Element|NULL findOneBy(array $criteria, array $orderBy = null)
 * @method Element[] findAll()
 * @method Element[] findBy(array $criteria, array $orderBy = null, ?int $limit = null, ?int $offset = null)
 * @extends AbstractRepository<Element>
 */
class ElementRepository extends AbstractRepository
{

	public function fromButton(array $component): ?Element
	{
		if ($component && is_array($component) && isset($component['id']) && isset($component['mailingId'])) {
			$element = $this->findOneBy([
				'composerId' => $component['id'],
				'mailing' => $component['mailingId'],
			]);

			if (!$element) {
				$element = new Element();
			}

			$element->setComposerId($component['id']);
			$element->setTitle($component['content']);
			$element->setRedirectToUrl($component['link']);

			/** @var Mailing|null $mailing */
			$mailing = $this->getEntityManager()->getRepository(Mailing::class)->find($component['mailingId']);

			if ($mailing !== null) {
				$element->setMailing($mailing);
			}

			$this->getEntityManager()->persist($element);
			$this->getEntityManager()->flush();

			return $element;
		}

		return null;
	}

}