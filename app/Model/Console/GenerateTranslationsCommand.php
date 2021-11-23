<?php declare(strict_types = 1);

namespace App\Model\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Vodacek\GettextExtractor\NetteExtractor;

class GenerateTranslationsCommand extends Command
{

	protected function configure()
	{
		parent::configure();

		$this->setName('app:generateTranslations')->setDescription('Generates the base .POT file for translations.');
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$pf = new NetteExtractor(APP_DIR . '/../log/gettextextractor.log');
		$pf->setupForms();
		$pf->setupDataGrid();
		$pf->scan(APP_DIR);
		$pf->save(LANG_DIR . '/messages.pot');
		unset($pf);

		$output->writeln('Translations generated.');

		return 0;
	}

}
