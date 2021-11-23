<?php declare(strict_types = 1);

namespace App\Model\Database\Fixtures;

use App\Model\Database\Entity\Language;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LanguageFixture implements FixtureInterface, OrderedFixtureInterface {

	/**
	 * Load data fixtures with the passed ObjectManager
     *
     * @param ObjectManager $manager
	 */
	public function load(ObjectManager $manager): void {
		$language = new Language();
		$language->setTitle('English');
		$language->setCode('en');
		$manager->persist($language);
		$manager->flush();

		$language = new Language();
		$language->setTitle('Čeština');
		$language->setCode('cs');
		$manager->persist($language);
		$manager->flush();
	}

	/**
	 * Get the order of this fixture
	 */
	public function getOrder(): int {
		return 1;
	}

}
