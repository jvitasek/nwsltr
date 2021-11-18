<?php declare(strict_types = 1);

namespace App\Modules\Base;

use App\Model\App;
use App\Model\Database\Entity\Account;
use App\Model\Database\Entity\User;
use Nette\Application\AbortException;
use Nette\Application\Attributes\Persistent;
use Nette\Application\UI\ComponentReflection;
use Nette\DI\Attributes\Inject;
use Nette\Localization\Translator;
use Nette\Security\UserStorage;
use Tracy\Debugger;

abstract class SecuredPresenter extends BasePresenter
{

	protected User $userEntity;
	protected ?int $accountId;
	protected ?Account $account;

	#[Persistent]
	public string $locale;

	#[Inject]
	public Translator $translator;

	/**
	 * @param ComponentReflection|mixed $element
	 * @throws AbortException
	 */
	public function checkRequirements($element): void
	{
		if (!$this->user->isLoggedIn()) {
			if ($this->user->getLogoutReason() === UserStorage::LOGOUT_INACTIVITY) {
				$this->flashInfo('You have been logged out for inactivity');
			}

			$this->redirect(App::DESTINATION_SIGN_IN, ['backlink' => $this->storeRequest()]);
		}
	}

	public function startup()
	{
		parent::startup();

		/** modal variables */
		if (!isset($this->template->modalTitle)) {
			$this->template->modalTitle = '';
		}

		if (!isset($this->template->modalContent)) {
			$this->template->modalContent = '';
		}

		if (!isset($this->template->modalType)) {
			$this->template->modalType = '';
		}

		if (!isset($this->template->modalValues)) {
			$this->template->modalValues = [];
		}

		if (!isset($this->template->openModal)) {
			$this->template->openModal = false;
		}

		if ($this->user->isLoggedIn()) {
			/** @var User $user */
			$user = $this->em->getRepository(User::class)->find($this->user->getId());
			$this->template->userEntity = $this->userEntity = $user;

			$this->template->accountId = $this->accountId = $this->accountSession->get();
			$this->template->selectedAccount = $this->account = $this->em->getRepository(Account::class)->find($this->accountId);
		}

		$this->template->locale = $this->translator->getLocale();

		// we check if the user did not spoof the session
		// injecting another account ID which does not belong
		// to him - if so, we empty the session and log him out

		if ($this->user->isLoggedIn() && !$this->userEntity->getAccounts()->contains($this->account)) {
			$this->accountSession->empty();
			$this->user->logout();
			$this->flashError('You were accessing an account for which you have no authorization');
			$this->redirect(App::DESTINATION_AFTER_SIGN_OUT);
		}
	}

	public function handleSwitchAccount(int $accountId): void
	{
		$this->accountSession->set($accountId);
		$this->redirect('this');
	}

	public function handleChangeLang($lang) {
		$this->locale = $lang;
		$this->translator->setLocale($lang);
		$this->template->locale = $lang;
		$this->redirect('this', ['locale' => $lang]);
	}

}
