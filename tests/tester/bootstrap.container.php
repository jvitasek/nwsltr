<?php declare(strict_types = 1);

use Nette\Configurator;

$rootDir = __DIR__ . '/../..';
require_once __DIR__ . '/bootstrap.php';

$configurator = new Configurator();
$configurator->setTempDirectory(TEMP_DIR);
$configurator->enableTracy(TEMP_DIR . '/../log');
$configurator->addConfig($rootDir . '/config/test.neon');
$configurator->addConfig($rootDir . '/config/config.neon');
$configurator->setDebugMode(true);
$configurator->addParameters([
	'rootDir' => $rootDir,
	'appDir' => $rootDir . '/app',
	'wwwDir' => $rootDir . '/www',
]);

return $configurator->createContainer();