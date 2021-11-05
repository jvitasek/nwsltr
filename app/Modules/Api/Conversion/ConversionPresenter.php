<?php declare(strict_types = 1);

namespace App\Modules\Api\Conversion;

use App\Model\Database\Entity\Conversion;
use App\Model\Database\Entity\Element;
use App\Model\Database\Entity\Queue;
use App\Modules\Api\BaseApiPresenter;
use Nette\Application\BadRequestException;
use Nette\Utils\Strings;

final class ConversionPresenter extends BaseApiPresenter
{

	public function actionRead(string $queueHash, string $elementId): void
	{
		/** @var Queue|null $queue */
		$queue = $this->em->getRepository(Queue::class)->findOneBy(['hash' => $queueHash]);

		/** @var Element|null $element */
		$element = $this->em->getRepository(Element::class)->findOneBy([
			'composerId' => $elementId,
			'mailing' => $queue->getMailing(),
		]);

		if ($queue !== null && $element !== null) {
			/** @var Conversion|null $conversion */
			$conversion = $this->em->getRepository(Conversion::class)->findOneBy([
				'queue' => $queue,
				'element' => $element,
			]);

			// we only log the conversion
			// for the first time for each user
			// so that an attacker cannot overflow
			// the database with bogus conversions

			if ($conversion === null) {
				$conversion = new Conversion();
				$conversion->setQueue($queue);
				$conversion->setElement($element);
				$this->em->persist($conversion);
				$this->em->flush();
			}

			if ($element->getRedirectToUrl()) {
				$redirectUrl = $element->getRedirectToUrl();
				$redirectUrl .= sprintf(
					'%sutm_source=newsletter&utm_campaign=%s&utm_medium=%s',
					(parse_url($redirectUrl, PHP_URL_QUERY) ? '&' : '?'),
					Strings::webalize($queue->getMailing()->getSubject()),
					Strings::webalize($element->getTitle())
				);
				$this->redirectUrl($redirectUrl);
			}
		}

		throw new BadRequestException();
	}

}
