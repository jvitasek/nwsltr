<?php declare(strict_types = 1);

namespace App\Modules\Base;

use App\Model\Database\EntityManager;
use App\Model\Session\AccountSession;
use App\UI\Control\TFlashMessage;
use App\UI\Control\TModuleUtils;
use Nette\Application\UI\Presenter;
use Contributte\Application\UI\Presenter\StructuredTemplates;

abstract class BasePresenter extends Presenter
{

	use StructuredTemplates;
	use TFlashMessage;
	use TModuleUtils;

	protected EntityManager $em;
	protected AccountSession $accountSession;

	/**
	 * SecuredPresenter constructor.
	 *
	 * @param EntityManager $entityManager
	 * @param AccountSession $accountSession
	 */
	public function __construct(EntityManager $entityManager, AccountSession $accountSession)
	{
		parent::__construct();

		$this->em = $entityManager;
		$this->accountSession = $accountSession;
	}

}