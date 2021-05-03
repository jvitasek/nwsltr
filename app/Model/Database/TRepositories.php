<?php declare(strict_types = 1);

namespace App\Model\Database;

use App\Model\Database\Entity\AbstractEntity;
use App\Model\Database\Entity\Account;
use App\Model\Database\Entity\Conversion;
use App\Model\Database\Entity\Element;
use App\Model\Database\Entity\Mailing;
use App\Model\Database\Entity\Queue;
use App\Model\Database\Entity\Recipient;
use App\Model\Database\Entity\RecipientGroup;
use App\Model\Database\Entity\Sendout;
use App\Model\Database\Entity\Unsubscribe;
use App\Model\Database\Entity\User;
use App\Model\Database\Repository\AccountRepository;
use App\Model\Database\Repository\ConversionRepository;
use App\Model\Database\Repository\ElementRepository;
use App\Model\Database\Repository\MailingRepository;
use App\Model\Database\Repository\QueueRepository;
use App\Model\Database\Repository\RecipientGroupRepository;
use App\Model\Database\Repository\RecipientRepository;
use App\Model\Database\Repository\SendoutRepository;
use App\Model\Database\Repository\UnsubscribeRepository;
use App\Model\Database\Repository\UserRepository;

/** @mixin EntityManager */
trait TRepositories
{

	public function getAccountRepository(): AccountRepository
	{
		/** @var AccountRepository $accountRepository */
		$accountRepository = $this->getRepository(Account::class);
		return $accountRepository;
	}

	public function getConversionRepository(): ConversionRepository
	{
		/** @var ConversionRepository $conversionRepository */
		$conversionRepository = $this->getRepository(Conversion::class);
		return $conversionRepository;
	}

	public function getElementRepository(): ElementRepository
	{
		/** @var ElementRepository $elementRepository */
		$elementRepository = $this->getRepository(Element::class);
		return $elementRepository;
	}

	public function getMailingRepository(): MailingRepository
	{
		/** @var MailingRepository $mailingRepository */
		$mailingRepository = $this->getRepository(Mailing::class);
		return $mailingRepository;
	}

	public function getQueueRepository(): QueueRepository
	{
		/** @var QueueRepository $queueRepository */
		$queueRepository = $this->getRepository(Queue::class);
		return $queueRepository;
	}

	public function getRecipientRepository(): RecipientRepository
	{
		/** @var RecipientRepository $recipientRepository */
		$recipientRepository = $this->getRepository(Recipient::class);
		return $recipientRepository;
	}

	public function getRecipientGroupRepository(): RecipientGroupRepository
	{
		/** @var RecipientGroupRepository $recipientGroupRepository */
		$recipientGroupRepository = $this->getRepository(RecipientGroup::class);
		return $recipientGroupRepository;
	}

	public function getSendoutRepository(): SendoutRepository
	{
		/** @var SendoutRepository $sendoutRepository */
		$sendoutRepository = $this->getRepository(Sendout::class);
		return $sendoutRepository;
	}

	public function getUnsubscribeRepository(): UnsubscribeRepository
	{
		/** @var UnsubscribeRepository $unsubscribeRepository */
		$unsubscribeRepository = $this->getRepository(Unsubscribe::class);
		return $unsubscribeRepository;
	}

	public function getUserRepository(): UserRepository
	{
		/** @var UserRepository $userRepository */
		$userRepository = $this->getRepository(User::class);
		return $userRepository;
	}

}