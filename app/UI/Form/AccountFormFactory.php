<?php declare(strict_types = 1);

namespace App\UI\Form;

use App\Model\Database\Entity\Account;
use App\Modules\Base\BasePresenter;
use Contributte\FormsBootstrap\BootstrapForm;
use JetBrains\PhpStorm\NoReturn;
use Nette\Application\UI\Form;
use Nette\Http\FileUpload;
use Nette\Utils\FileSystem;
use Nette\Utils\Image;
use Nette\Utils\Random;

class AccountFormFactory extends BaseFormFactory
{

	/** @return Form|BootstrapForm */
	public function create(): Form|BootstrapForm
	{
		$form = new BootstrapForm();
		$form->addHidden('id');
		$form->addText('title', 'Title')->setRequired();

		$form->addEmail('emailFrom', 'From (e-mail)')->setRequired(false);
		$form->addText('emailFromTitle', 'From (title)')->setRequired(false);
		$form->addEmail('emailReplyTo', 'Reply To')->setRequired(false);

		$form->addText('websiteUrl', 'Website URL')->setRequired(false);
		$form->addText('unsubscribeRedirectUrl', 'Redirect after unsubscribe')->setRequired(false);

		$form->addCheckbox('showUnsubscribeFeedbackForm', 'Show unsubscribe feedback form')->setRequired(false);
		$form->addCheckbox('showResubscribeButton', 'Show resubscribe button after unsubscribe')->setRequired(false);

		$form->addUpload('logo', 'Logo (the image will be resized to 400px width)')->setRequired(false);
		$form->addText('primaryColorHex', 'Primary Color HEX')->addRule(Form::LENGTH, 'Enter 6 characters', 6)->setRequired(false);
		$form->addText('secondaryColorHex', 'Secondary Color HEX')->addRule(Form::LENGTH, 'Enter 6 characters', 6)->setRequired(false);

		$form->addText('smtpHost', 'SMTP: host');
		$form->addText('smtpUsername', 'SMTP: username');
		$form->addPassword('smtpPassword', 'SMTP: password');
		$form->addText('smtpSecure', 'SMTP: secure (ssl/tls)');

		$form->addSubmit('submit', 'Submit');
		$form->onSuccess[] = [$this, 'success'];

		return $form;
	}

	#[NoReturn] public function success(Form $form): void
	{
		$values = $form->getValues();
		/** @var BasePresenter $presenter */
		$presenter = $form->getPresenter();

		if ($values->id) {
			$account = $this->em->getRepository(Account::class)->find((int) $values->id);
		} else {
			$account = new Account();
		}

		$account->setTitle($values->title);
		$account->setEmailFrom($values->emailFrom);
		$account->setEmailFromTitle($values->emailFromTitle);
		$account->setEmailReplyTo($values->emailReplyTo);
		$account->setWebsiteUrl($values->websiteUrl);
		$account->setUnsubscribeRedirectUrl($values->unsubscribeRedirectUrl);
		$account->setShowUnsubscribeFeedbackForm($values->showUnsubscribeFeedbackForm);
		$account->setShowResubscribeButton($values->showResubscribeButton);

		// LOGO
		if ($values->logo) {
			/** @var FileUpload $file */
			$file = $values->logo;

			if ($file instanceof FileUpload && $file->isOk() && $file->isImage()) {
				$image = Image::fromFile((string) $file);
				$image->resize(400, null);

				$fileName = Random::generate();
				$fileExtension = $file->getImageFileExtension();
				$path = '/uploads/logo/' . $fileName . '.' . $fileExtension;
				$image->save(WWW_DIR . $path);
				$account->setLogo($path);
			}
		}

		$account->setPrimaryColorHex($values->primaryColorHex);
		$account->setSecondaryColorHex($values->secondaryColorHex);

		$account->setSmtpHost($values->smtpHost);
		$account->setSmtpUsername($values->smtpUsername);

		if ($values->smtpPassword) {
			$account->setSmtpPassword($values->smtpPassword);
		}

		$account->setSmtpSecure($values->smtpSecure);

		$this->em->persist($account);
		$this->em->flush();

		$presenter->flashSuccess('Account saved successfully');
		$presenter->redirect('Account:list');
	}

}