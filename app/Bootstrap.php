<?php declare(strict_types = 1);

namespace App;

use Nette\Bootstrap\Configurator;
use Tracy\Debugger;

class Bootstrap
{

	public static function boot(): Configurator
	{
		require __DIR__ . '/constants.php';

		if (PHP_SAPI === 'cli') {
			ini_set('memory_limit', '256M');
		}

		$configurator = new Configurator();
		$appDir = dirname(APP_DIR);

		//$configurator->setDebugMode('secret@23.75.345.200'); // enable for your remote IP
		$configurator->enableTracy($appDir . '/log');

		$configurator->setTimeZone('Europe/Prague');
		$configurator->setTempDirectory($appDir . '/temp');

		$configurator->addParameters([
			'rootDir' => realpath(__DIR__ . '/..'),
			'appDir' => APP_DIR,
			'wwwDir' => realpath(__DIR__ . '/../www'),
		]);

		$configurator->createRobotLoader()
			->addDirectory(__DIR__)
			->register();

		$configurator->addConfig(CONFIG_DIR . '/config.neon');

		if (file_exists(CONFIG_DIR . '/test.neon')) {
			Debugger::enable(Debugger::DEVELOPMENT);
			$configurator->setDebugMode(true);
			$configurator->addConfig(CONFIG_DIR . '/test.neon');
		} else {
			Debugger::enable(Debugger::PRODUCTION);
			$configurator->setDebugMode(['192.168.0.117']);
			$configurator->addConfig(CONFIG_DIR . '/production.neon');
		}

		return $configurator;
	}

}
