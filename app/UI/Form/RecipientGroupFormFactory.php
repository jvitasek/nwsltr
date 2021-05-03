<?php declare(strict_types = 1);

namespace App\UI\Form;

use App\Model\App;
use App\Model\Database\Entity\RecipientGroup;
use App\Model\Exception\Runtime\AuthenticationException;
use App\Modules\Base\BasePresenter;
use App\Modules\Front\Sign\SignPresenter;
use Contributte\FormsBootstrap\BootstrapForm;
use Doctrine\DBAL\Exception;
use Nette\Application\UI\Form;

class RecipientGroupFormFactory extends BaseFormFactory
{

	/** @return Form */
	public function create()
	{
		$form = new BootstrapForm();
		$form->addHidden('id');
		$form->addText('title', 'Title')->setRequired(true);
		$form->addSubmit('submit', 'Submit');
		$form->onSuccess[] = [$this, 'success'];

		return $form;
	}

	public function success(Form $form): void
	{
		$values = $form->getValues();
		/** @var BasePresenter $presenter */
		$presenter = $form->getPresenter();

		if ($values->id) {
			$recipientGroup = $this->em->getRepository(RecipientGroup::class)->find($values->id);
		} else {
			$recipientGroup = new RecipientGroup();
		}

		$recipientGroup->setTitle($values->title);
		$recipientGroup->setAccount($this->account);

		try {
			$this->em->persist($recipientGroup);
			$this->em->flush();
		} catch (Exception $e) {
			$presenter->flashError('This recipient group cannot be saved.');
			$presenter->redirect('this');
		}

		$presenter->flashSuccess('Recipient group saved successfully');
		$presenter->redirect('RecipientGroup:list');
	}

}