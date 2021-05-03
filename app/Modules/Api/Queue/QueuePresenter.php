<?php declare(strict_types = 1);

namespace App\Modules\Api\Queue;

use App\Model\Database\Entity\Queue;
use App\Modules\Api\BaseApiPresenter;
use Nette\Application\LinkGenerator;
use Nette\DI\Attributes\Inject;

final class QueuePresenter extends BaseApiPresenter
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
			$queue->setOpened(true);
			$this->em->persist($queue);
			$this->em->flush();
		}

		$graphicPath = $this->linkGenerator->link('Front:Home:default') . 'images/blank.gif';
		header('Pragma: public');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Cache-Control: private', false);
		header('Content-Disposition: attachment; filename="blank.gif"');
		header('Content-Transfer-Encoding: binary');
		header('Content-Length: ' . 42);
		readfile($graphicPath);
		exit;
	}

}