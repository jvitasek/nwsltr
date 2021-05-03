<?php declare(strict_types = 1);

namespace App\UI\Form;

use App\Model\Database\Entity\Account;
use App\Model\Database\EntityManager;
use App\Model\Session\AccountSession;
use Nette\SmartObject;

abstract class BaseFormFactory
{

	use SmartObject;

	protected EntityManager $em;
	protected AccountSession $accountSession;
	protected ?Account $account;

	/**
	 * SecuredPresenter constructor.
	 *
	 * @param EntityManager $entityManager
	 * @param AccountSession $accountSession
	 */
	public function __construct(EntityManager $entityManager, AccountSession $accountSession)
	{
		$this->em = $entityManager;
		$this->accountSession = $accountSession;
		$account = $this->accountSession->get();

		if ($account) {
			$this->account = $this->em->getRepository(Account::class)->find($this->accountSession->get());
		}
	}

}