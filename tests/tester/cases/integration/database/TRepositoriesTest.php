<?php

use App\Model\Database\EntityManager;
use App\Model\Database\TRepositories;
use Doctrine\ORM\Mapping\ClassMetadata;
use Nette\DI\Container;
use Tester\Assert;

require __DIR__ . '/../../../bootstrap.php';

/** @var Container $container */
$container = require_once NETTE_TESTER_DIR . '/bootstrap.container.php';

/** @var EntityManager $em */
$em = $container->getByType(EntityManager::class);

/** @var ClassMetadata[] $metadata */
$metadata = $em->getMetadataFactory()->getAllMetadata();

foreach ($metadata as $item) {
	$entityClass = $item->getName();
	$methodName = 'get' . (new ReflectionClass($entityClass))->getShortName() . 'Repository';
	Assert::true(
		method_exists($em, $methodName),
		sprintf('Method %s() not exist in %s or %s', $methodName, TRepositories::class, EntityManager::class)
	);
	Assert::same($em->getRepository($entityClass), $em->$methodName());
}