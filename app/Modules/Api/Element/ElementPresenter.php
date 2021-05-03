<?php declare(strict_types = 1);

namespace App\Modules\Api\Element;

use App\Model\Database\Entity\Element;
use App\Model\Database\Entity\Mailing;
use App\Model\Database\Repository\ElementRepository;
use App\Modules\Api\BaseApiPresenter;

final class ElementPresenter extends BaseApiPresenter
{

	public function actionCreate(): void
	{
		$post = json_decode($this->getHttpRequest()->getRawBody(), true);
		/** @var ElementRepository $elementRepository */
		$elementRepository = $this->em->getRepository(Element::class);
		$element = $elementRepository->fromButton($post);

		if ($element instanceof Element) {
			$this->getHttpResponse()->setCode(200);
			$this->sendJson(['result' => 'ok', 'link' => $this->link('Conversion:read', '[[QUEUE_HASH]]', $element->getId())]);
		} else {
			$this->getHttpResponse()->setCode(404);
			$this->sendJson(['result' => 'nok']);
		}
	}

}