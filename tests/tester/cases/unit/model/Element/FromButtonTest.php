<?php

use App\Model\Database\Entity\Element;
use App\Model\Database\EntityManager;
use Nette\DI\Container;
use Nette\Utils\Random;
use Tester\Assert;

require __DIR__ . '/../../../../bootstrap.php';

/** @var Container $container */
$container = require_once NETTE_TESTER_DIR . '/bootstrap.container.php';

$em = $container->getByType(EntityManager::class);

$originalLink = '//google.com';
$button = [
	'id' => (int) Random::generate(5, '0-9'), // just for test purposes
	'content' => 'Test button',
	'link' => $originalLink,
	'mailingId' => (int) Random::generate(5, '0-9'), // just for test purposes
];
$element = $em->getElementRepository()->fromButton($button);
Assert::type($element, new Element());
Assert::equal($element->getRedirectToUrl(), $originalLink);