<?php declare(strict_types = 1);

namespace App\UI\Form;

use App\Model\App;
use App\Model\Exception\Runtime\AuthenticationException;
use App\Modules\Front\Sign\SignPresenter;
use Nette\Application\UI\Form;

class SignInFormFactory extends BaseFormFactory
{

	/** @return Form */
	public function create()
	{
		$form = new Form();
		$form->addEmail('email', 'E-mail')->setRequired(true);
		$form->addPassword('password', 'Password')->setRequired(true);
		$form->addCheckbox('remember', 'Remember?')->setDefaultValue(true);
		$form->onSuccess[] = [$this, 'success'];

		return $form;
	}

	public function success(Form $form): void
	{
		$values = $form->getValues();
		/** @var SignPresenter $presenter */
		$presenter = $form->getPresenter();

		try {
			$presenter->user->setExpiration($values->remember ? '14 days' : '20 minutes');
			$presenter->user->login($values->email, $values->password);
		} catch (AuthenticationException $e) {
			$presenter->flashWarning('Sign in failed');
			$presenter->redirect('this');
		}

		if ($presenter->backlink) {
			$presenter->restoreRequest($presenter->backlink);
		}

		$presenter->redirect(App::DESTINATION_AFTER_SIGN_IN);
	}

}