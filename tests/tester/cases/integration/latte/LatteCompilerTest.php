<?php

use App\Model\Latte\TemplateFactory;
use Nette\Application\UI\ITemplateFactory;
use Nette\Bridges\ApplicationLatte\Template;
use Nette\DI\Container;
use Nette\Utils\Finder;
use Tester\Assert;

require __DIR__ . '/../../../bootstrap.php';

/** @var Container $container */
$container = require_once NETTE_TESTER_DIR . '/bootstrap.container.php';

/** @var ITemplateFactory $templateFactory */
$templateFactory = $container->getByType(ITemplateFactory::class);
Assert::type(TemplateFactory::class, $templateFactory);

/** @var Template $template */
$template = $templateFactory->createTemplate();
$finder = Finder::findFiles('*.latte')->from(APP_DIR);

try {
	/** @var SplFileInfo $file */
	foreach ($finder as $file) {
		$template->getLatte()->warmupCache($file->getRealPath());
	}
} catch (Throwable $e) {
	Assert::fail(sprintf('Latte template compilation failed ([%s] %s)', get_class($e), $e->getMessage()));
}