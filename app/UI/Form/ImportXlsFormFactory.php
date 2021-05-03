<?php declare(strict_types = 1);

namespace App\UI\Form;

use App\Domain\Import\ImportFromExcel;
use App\Model\Database\Entity\RecipientGroup;
use App\Model\Database\EntityManager;
use App\Model\Session\AccountSession;
use App\Modules\Base\BasePresenter;
use Contributte\FormsBootstrap\BootstrapForm;
use JetBrains\PhpStorm\NoReturn;
use Nette\Application\UI\Form;

class ImportXlsFormFactory extends BaseFormFactory
{

	/** @var ImportFromExcel */
	private ImportFromExcel $importFromExcel;

	/**
	 * ImportXlsFormFactory constructor.
	 *
	 * @param EntityManager $entityManager
	 * @param AccountSession $accountSession
	 * @param ImportFromExcel $importFromExcel
	 */
	public function __construct(
        EntityManager $entityManager,
        AccountSession $accountSession,
        ImportFromExcel $importFromExcel
    )
	{
		parent::__construct($entityManager, $accountSession);

		$this->importFromExcel = $importFromExcel;
	}

	/** @return Form|BootstrapForm */
	public function create(): Form|BootstrapForm
	{
		$form = new BootstrapForm();
		$form->addHidden('id')->setRequired();
		$form->addUpload('file', 'File')->setRequired();
		$form->addCheckbox('replace', 'Replace current recipients?')->setHtmlAttribute('class', 'form-control')->setDefaultValue(false);
		$form->addSubmit('submit', 'Submit');
		$form->onSuccess[] = [$this, 'success'];

		return $form;
	}

	#[NoReturn] public function success(Form $form): void
	{
		$values = $form->getValues();
		/** @var BasePresenter $presenter */
		$presenter = $form->getPresenter();

		/** @var RecipientGroup $recipientGroup */
		$recipientGroup = $this->em->getRepository(RecipientGroup::class)->findOneBy([
			'id' => (int) $values->id,
			'account' => $this->account,
		]);

		if ($recipientGroup !== null) {
			$numberOfRecipientsBeforeImport = $recipientGroup->getRecipients()->count();
			$this->importFromExcel->setFile($values->file);
			$this->importFromExcel->setRecipientGroup($recipientGroup);
			$this->importFromExcel->setReplaceCurrentRecipients((bool) $values->replace);
			$this->importFromExcel->setAccount($this->account);

			if ($this->importFromExcel->execute()) {
				if ($values->replace) {
					$imported = $recipientGroup->getRecipients()->count();
				} else {
					$imported = $recipientGroup->getRecipients()->count() - $numberOfRecipientsBeforeImport;
				}

				$presenter->flashSuccess('Successfuly imported, new recipients: ' . $imported);
				$presenter->redirect('this');
			}
		}

		$presenter->flashError('Import failed');
		$presenter->redirect('this');
	}

}