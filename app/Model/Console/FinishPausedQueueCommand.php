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

class FinishPausedQueueCommand extends Command
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
		$this->setName('mailing:finishPausedQueue');
		$this->setDescription('Sends unsent mailings from queue');
		$this->addArgument('mailing', NULL, 'The ID of the mailing', NULL);
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$mailing = $input->getArgument('mailing');
		if (!$mailing) {
			$output->writeln('No mailing ID supplied!');
			return 1;
		}

		/** @var Mailing $mailing */
		$mailing = $this->em->getRepository(Mailing::class)->find($mailing);

		if ($mailing && $mailing->isSending()) {
			$queueRepository = $this->em->getRepository(Queue::class);
			$queueRepository->emptyQueue($mailing, $this->linkGenerator, $mailing->getLatestSendout());
		}

		return 0;
	}

}
