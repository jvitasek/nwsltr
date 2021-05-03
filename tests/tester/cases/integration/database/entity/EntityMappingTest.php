<?php

use App\Model\Database\EntityManager;
use Doctrine\ORM\Tools\SchemaValidator;
use Nette\DI\Container;
use Tester\Assert;

require __DIR__ . '/../../../../bootstrap.php';

/** @var Container $container */
$container = require_once NETTE_TESTER_DIR . '/bootstrap.container.php';

$em = $container->getByType(EntityManager::class);
$validator = new SchemaValidator($em);
$validations = $validator->validateMapping();
foreach ($validations as $fails) {
	foreach ((array) $fails as $fail) {
		Assert::fail($fail);
	}
}
Assert::count(0, $validations);