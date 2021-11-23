<?php declare(strict_types = 1);

namespace App\Model\Database\Fixtures;

use App\Model\Database\Entity\Account;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AccountFixture implements FixtureInterface, OrderedFixtureInterface {

	/**
	 * Load data fixtures with the passed ObjectManager
     *
     * @param ObjectManager $manager
	 */
	public function load(ObjectManager $manager): void {
		$account = new Account();
		$account->setTitle('Sample');
		$account->setWebsiteUrl('https://google.com');
		$account->setShowResubscribeButton(true);
		$account->setShowUnsubscribeFeedbackForm(true);
		$account->setEmailFrom('sample@sample.com');
		$account->setEmailFromTitle('Sample NWSLTR Account');
		$account->setEmailReplyTo('sample@sample.com');
		$account->setPrimaryColorHex('E74C3C');
		$account->setSecondaryColorHex('ECF0F1');
		$account->setLogo('/uploads/logo/sample.png');
		$account->setSmtpHost('smtp.mailtrap.io');
		$account->setSmtpUsername('74993315e661a6');
		$account->setSmtpPassword('6df992897adb59');
		$account->setSmtpSecure('tls');
		$account->setSmtpPort('2525');
		$manager->persist($account);
		$manager->flush();
	}

	/**
	 * Get the order of this fixture
	 */
	public function getOrder(): int {
		return 2;
	}

}
