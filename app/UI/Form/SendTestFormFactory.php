<?php declare(strict_types = 1);

namespace App\UI\Form;

use App\Model\Database\Entity\Mailing;
use App\Model\Database\EntityManager;
use App\Model\Session\AccountSession;
use App\Modules\Base\BasePresenter;
use Contributte\FormsBootstrap\BootstrapForm;
use JetBrains\PhpStorm\NoReturn;
use Nette\Application\LinkGenerator;
use Nette\Application\UI\Form;
use Nette\Utils\Validators;

class SendTestFormFactory extends BaseFormFactory
{

	private LinkGenerator $linkGenerator;

	/**
	 * SendTestFormFactory constructor.
	 *
	 * @param LinkGenerator $linkGenerator
	 * @param EntityManager $entityManager
	 * @param AccountSession $accountSession
	 */
	public function __construct(
		LinkGenerator $linkGenerator,
		EntityManager $entityManager,
		AccountSession $accountSession
	)
	{
		parent::__construct($entityManager, $accountSession);

		$this->linkGenerator = $linkGenerator;
	}

	/** @return Form|BootstrapForm */
	public function create(): Form|BootstrapForm
	{
		$form = new BootstrapForm();
		$form->addHidden('id')->setRequired();
		$form->addEmail('email', 'E-mail')->setRequired();
		$form->addSubmit('submit', 'Submit');
		$form->onSuccess[] = [$this, 'success'];

		return $form;
	}

	#[NoReturn] public function success(Form $form): void
	{
		$values = $form->getValues();
		/** @var BasePresenter $presenter */
		$presenter = $form->getPresenter();

		/** @var Mailing $mailing */
		$mailing = $this->em->getRepository(Mailing::class)->findOneBy([
			'id' => $values->id,
			'account' => $this->accountSession->get(),
		]);

		$email = trim($values->email);

		if ($mailing !== null && Validators::isEmail($email) && $mailing->isOkToBeTestSent()) {
			if ($mailing->send($this->linkGenerator, $email)) {
				$presenter->flashSuccess('Test sendout finished successfully');
				$presenter->redirect('this');
			} else {
				$presenter->flashWarning('Test sendout was not finished');
				$presenter->redirect('this');
			}
		} else {
			$presenter->flashError('Test sendout failed');
			$presenter->redirect('this');
		}
	}

}