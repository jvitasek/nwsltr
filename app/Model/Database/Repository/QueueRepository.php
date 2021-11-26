<?php declare(strict_types = 1);

namespace App\Model\Database\Repository;

use App\Model\Database\Entity\Mailing;
use App\Model\Database\Entity\Queue;
use App\Model\Database\Entity\Sendout;
use Nette\Application\LinkGenerator;
use Nette\Mail\Mailer;
use Nette\Utils\Validators;
use Tracy\Debugger;

/**
 * @method Queue|NULL find($id, ?int $lockMode = null, ?int $lockVersion = null)
 * @method Queue|NULL findOneBy(array $criteria, array $orderBy = null)
 * @method Queue[] findAll()
 * @method Queue[] findBy(array $criteria, array $orderBy = null, ?int $limit = null, ?int $offset = null)
 * @extends AbstractRepository<Queue>
 */
class QueueRepository extends AbstractRepository
{
	public const THROTTLE_MICROSECONDS = 500000;

	public function emptyQueue(Mailing $mailing, LinkGenerator $linkGenerator, Sendout $sendout): bool
	{
		$logFile = 'sendout-' . $sendout->getId();
		$mailer = $mailing->getAccount()->createSmtpMailer();
		$queueToSend = $this->findBy([
			'sent' => 0,
			'mailing' => $mailing,
		]);
		Debugger::log('Started sendout of mailing with ID: ' . $mailing->getId(), $logFile);

		$successes = 0;
		$errors = 0;
		$successiveErrors = 0;
		foreach ($queueToSend as $queue) {
			if ($this->sendItem($queue, $mailer, $linkGenerator, $logFile)) {
				$successes++;
				$successiveErrors = 0;
			} else {
				$errors++;
				$successiveErrors++;
			}

			if ($successiveErrors === 3) {
				Debugger::log(
					sprintf(
						'Failing sendout of mailing ID: %s, %s successive errors',
						$mailing->getId(),
						$successiveErrors
					),
					$logFile
				);
				return false;
			}
		}

		Debugger::log(
			sprintf(
				'Finished sendout of mailing with ID: %s, successes: %s, errors: %s',
				$mailing->getId(),
				$successes,
				$errors
			),
			$logFile
		);
		return true;
	}

	public function sendItem(Queue $queue, Mailer $mailer, LinkGenerator $linkGenerator, string $logFile): bool
	{
		if (Validators::isEmail($queue->getEmail())) {
			if ($queue->getMailing()->send($mailer, $linkGenerator, $queue->getEmail(), $queue->getHash())) {
				$queue->setTimeSent(new \DateTime());
				$queue->setSent(true);
				$this->getEntityManager()->persist($queue);
				$this->getEntityManager()->flush();
				Debugger::log('Sent to: ' . $queue->getEmail(), $logFile);
			} else {
				Debugger::log('Sending failed to: ' . $queue->getEmail(), $logFile);
				return false;
			}
			sleep(self::THROTTLE_MICROSECONDS);
		} else {
			Debugger::log('E-mail is not valid: ' . $queue->getEmail(), $logFile);
			// not returning false here because it's not a hard error
		}
		return true;
	}

}
