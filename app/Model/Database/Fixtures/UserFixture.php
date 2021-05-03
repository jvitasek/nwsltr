<?php declare(strict_types = 1);

namespace App\Model\Database\Fixtures;

use App\Model\Database\Entity\Account;
use App\Model\Database\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class UserFixture implements FixtureInterface, OrderedFixtureInterface {

	/**
	 * Load data fixtures with the passed ObjectManager
	 * @param ObjectManager $manager
	 */
	public function load(ObjectManager $manager): void {
		/** @var Account $account */
		$account = $manager->getRepository(Account::class)->find(1);

		$user = new User();
		$user->addAccount($account);
		$user->setState(User::STATE_ACTIVATED);
		$user->setEmail('admin@sample.com');
		$user->setPassword('$2b$10$uu.7ssB2nxVUMKG7fZShQOtwe3N8/2I0E5niOL5OzXotpW7TPcjE2'); // the password is: secret
		$user->setRole(User::ROLE_ADMIN);
		$user->setFirstName('Test');
		$user->setLastName('Tester');
		$manager->persist($user);
		$manager->flush();
	}

	/**
	 * Get the order of this fixture
	 */
	public function getOrder(): int {
		return 2;
	}

}