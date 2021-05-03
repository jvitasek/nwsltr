<?php declare(strict_types = 1);

namespace App\Modules\Front\Sign;

use App\Model\App;
use App\Modules\Front\BaseFrontPresenter;
use App\UI\Form\SignInFormFactory;
use Nette\Application\Attributes\Persistent;
use Nette\Application\UI\Form;
use Nette\DI\Attributes\Inject;

final class SignPresenter extends BaseFrontPresenter
{

	#[Persistent]
	public ?string $backlink = null;
	#[Inject]
	public SignInFormFactory $signInFormFactory;

	public function checkRequirements($element): void
	{
	}

	public function actionIn(): void
	{
		$this->accountSession->empty();

		if ($this->user->isLoggedIn()) {
			$this->redirect(App::DESTINATION_AFTER_SIGN_IN);
		}
	}

	public function actionOut(): void
	{
		$this->accountSession->empty();

		if ($this->user->isLoggedIn()) {
			$this->user->logout();
			$this->flashSuccess('You were successfully logged out');
		}

		$this->redirect(App::DESTINATION_AFTER_SIGN_OUT);
	}

	/** @return Form */
	protected function createComponentSignInForm()
	{
		return $this->signInFormFactory->create();
	}

}