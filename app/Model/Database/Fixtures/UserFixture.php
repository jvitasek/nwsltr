<?php declare(strict_types = 1);

namespace App\Model\Database\Fixtures;

use App\Model\Database\Entity\Account;
use App\Model\Database\Entity\Language;
use App\Model\Database\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class UserFixture implements FixtureInterface, OrderedFixtureInterface
{

	/**
	 * Load data fixtures with the passed ObjectManager
	 *
	 * @param ObjectManager $manager
	 */
	public function load(ObjectManager $manager): void
	{
		/** @var Account $account */
		$account = $manager->getRepository(Account::class)->find(1);

		/** @var Language $language */
		$language = $manager->getRepository(Language::class)->find(1);

		$user = new User();
		$user->addAccount($account);
		$user->setState(User::STATE_ACTIVATED);
		$user->setEmail('admin@sample.com');
		$user->setPassword('secret');
		$user->setRole(User::ROLE_ADMIN);
		$user->setFirstName('Test');
		$user->setLastName('Tester');
		$user->setLanguage($language);
		$manager->persist($user);
		$manager->flush();
	}

	/**
	 * Get the order of this fixture
	 */
	public function getOrder(): int
	{
		return 3;
	}

}
