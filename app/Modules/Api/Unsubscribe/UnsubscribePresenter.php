<?php declare(strict_types = 1);

namespace App\Modules\Api\Unsubscribe;

use App\Model\Database\Entity\Queue;
use App\Model\Database\Entity\Recipient;
use App\Model\Database\Entity\Unsubscribe;
use App\Model\Database\Repository\RecipientRepository;
use App\Modules\Api\BaseApiPresenter;
use Nette\Application\BadRequestException;
use Nette\Application\LinkGenerator;
use Nette\DI\Attributes\Inject;

final class UnsubscribePresenter extends BaseApiPresenter
{

	#[Inject]
	public LinkGenerator $linkGenerator;

	public function actionRead(string $queueHash, string $mailingId): void
	{
		/** @var Queue|null $queue */
		$queue = $this->em->getRepository(Queue::class)->findOneBy([
			'hash' => $queueHash,
			'mailing' => $mailingId,
		]);

		if ($queue !== null) {
			/** @var RecipientRepository $recipientRepository */
			$recipientRepository = $this->em->getRepository(Recipient::class);
			$recipientsToUnsubscribe = $recipientRepository->findByQueue($queue, true);
			$unsubsCount = 0;

			foreach ($recipientsToUnsubscribe as $recipient) {
				$recipient->setSubscribed(false);
				$this->em->persist($recipient);
				$unsubsCount++;
			}

			$this->em->flush();

			if ($unsubsCount > 0) {
				$unsubscribe = new Unsubscribe();
				$unsubscribe->setEmail($queue->getEmail());
				$unsubscribe->setMailing($queue->getMailing());
				$this->em->persist($unsubscribe);
				$this->em->flush();

				if ($queue->getMailing()->getAccount()->getUnsubscribeRedirectUrl()) {
					$this->redirectUrl($queue->getMailing()->getAccount()->getUnsubscribeRedirectUrl());
				} else {
					$this->redirect(':Front:Unsubscribe:default', $queueHash, $mailingId, $unsubscribe->getId());
				}
			} else {
				$this->redirect(':Front:Unsubscribe:alreadyUnsubscribed');
			}
		} else {
			throw new BadRequestException();
		}
	}

}