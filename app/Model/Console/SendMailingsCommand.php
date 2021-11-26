<?php declare(strict_types = 1);

namespace App\Model\Console;

use App\Model\Database\Entity\Mailing;
use App\Model\Database\Entity\Queue;
use App\Model\Database\Entity\Recipient;
use App\Model\Database\Entity\Sendout;
use App\Model\Database\EntityManager;
use Nette\Application\LinkGenerator;
use Nette\Mail\Message;
use Nette\Mail\SmtpException;
use Nette\Mail\SmtpMailer;
use Nette\Utils\Validators;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Tracy\Debugger;

class SendMailingsCommand extends Command
{

	private EntityManager $em;
	private LinkGenerator $linkGenerator;

	/**
	 * SendMailingsCommand constructor.
	 *
	 * @param LinkGenerator $linkGenerator
	 * @param EntityManager $em
	 * @param string|null $name
	 */
	public function __construct(LinkGenerator $linkGenerator, EntityManager $em, ?string $name = null)
	{
		parent::__construct($name);

		$this->em = $em;
		$this->linkGenerator = $linkGenerator;
	}

	protected function configure(): void
	{
		$this->setName('mailing:send');
		$this->setDescription('Sends unsent mailings which are set to ready and have a sendout date in the past');
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$output->writeln('Starting sendout');

		$mailingsToSend = $this->em->getRepository(Mailing::class)
			->qb()
			->where('p.sendDate <= :now')->setParameter('now', new \DateTime())
			->andWhere('p.status = :readyStatus')->setParameter('readyStatus', Mailing::STATUS_READY)
			->orderBy('p.sendDate', 'ASC')
			->getQuery()->getResult();

		$output->writeln('Found mailings to send: ' . count($mailingsToSend));

		/** @var Mailing $mailing */
		foreach ($mailingsToSend as $mailing) {
			$isOkToBeSent = $mailing->isOkToBeSent();

			if ($isOkToBeSent !== true) {
				$output->writeln($isOkToBeSent);

				continue;
			}

			$mailing->setStatus(Mailing::STATUS_SENDING);
			$this->em->persist($mailing);
			$this->em->flush();

			$recipients = $mailing->getSubscribedRecipients();

			$output->writeln('Filling the queue with recipients');

			$sendout = new Sendout();
			$sendout->setMailing($mailing);
			$this->em->persist($sendout);
			$this->em->flush();

			$logFile = 'sendout-' . $sendout->getId();

			Debugger::log('Started sendout of mailing with ID: ' . $mailing->getId(), $logFile);

			$progress = new ProgressBar($output, $recipients->count());
			$progress->start();

			/** @var Recipient $recipient */
			foreach ($recipients as $recipient) {
				if (!Validators::isEmail($recipient->getEmail()) || !$recipient->isSubscribed()) {
					$progress->advance();

					continue;
				}

				$queueItem = new Queue();
				$queueItem->setEmail($recipient->getEmail());
				$queueItem->setMailing($mailing);
				$queueItem->setSendout($sendout);
				$this->em->persist($queueItem);

				$progress->advance();
			}

			Debugger::log('Queue filled with ' . $recipients->count() . ' recipients', $logFile);

			$progress->finish();
			$this->em->flush();

			$output->writeln('');

			$queueRepository = $this->em->getRepository(Queue::class);
			$queueRepository->emptyQueue($mailing, $this->linkGenerator, $sendout);

			$output->writeln('Finished sending mailing ' . $mailing->getId());
			$output->writeln('---');

			Debugger::log('Sendout finished', $logFile);
		}

		return 0;
	}

}
