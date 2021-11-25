<?php declare(strict_types = 1);

namespace App\Modules\Front\Unsubscribe;

use App\Model\Database\Entity\Queue;
use App\Model\Database\Entity\Recipient;
use App\Model\Database\Entity\Unsubscribe;
use App\Model\Database\Repository\RecipientRepository;
use App\Modules\Base\UnsecuredPresenter;
use App\Modules\Front\BaseFrontPresenter;
use App\UI\Form\UnsubscribeFormFactory;
use Nette\Application\BadRequestException;
use Nette\Application\UI\Form;
use Nette\DI\Attributes\Inject;

final class UnsubscribePresenter extends UnsecuredPresenter
{

    #[Inject]
    public UnsubscribeFormFactory $unsubscribeFormFactory;

	private Queue $queue;
	private Unsubscribe $unsubscribe;

	public function checkRequirements($element): void
	{
	}

	public function actionDefault(string $queueHash, int $mailingId, int $unsubscribeId): void
	{
		/** @var Queue|null $queue */
		$queue = $this->em->getRepository(Queue::class)->findOneBy([
			'hash' => $queueHash,
			'mailing' => $mailingId,
		]);

		/** @var Unsubscribe|null $unsubscribe */
		$unsubscribe = $this->em->getRepository(Unsubscribe::class)->find($unsubscribeId);

		if ($queue !== null && $unsubscribe !== null) {
			$this->queue = $queue;
			$this->unsubscribe = $unsubscribe;

			$this['unsubscribeForm']->setValues([
				'unsubscribeId' => $this->unsubscribe->getId(),
			]);
		} else {
			throw new BadRequestException();
		}
	}

	public function renderDefault(): void
	{
		$this->template->queue = $this->queue;
		$this->template->account = $this->queue->getMailing()->getAccount();
	}

	public function handleResubscribe(): void
	{
		$this->unsubscribe->setResubscribed(true);
		$this->em->persist($this->unsubscribe);
		$this->em->flush();

		/** @var RecipientRepository $recipientRepository */
		$recipientRepository = $this->em->getRepository(Recipient::class);
		$recipientsToResubscribe = $recipientRepository->findByQueue($this->queue, false);

		foreach ($recipientsToResubscribe as $recipient) {
			$recipient->setSubscribed(true);
			$this->em->persist($recipient);
		}

		$this->em->flush();
	}

	protected function createComponentUnsubscribeForm(): Form
	{
		return $this->unsubscribeFormFactory->create();
	}

}
