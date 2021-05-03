<?php declare(strict_types = 1);

namespace App\UI\Form;

use App\Model\Database\Entity\Recipient;
use App\Model\Database\Entity\RecipientGroup;
use App\Modules\Base\BasePresenter;
use Contributte\FormsBootstrap\BootstrapForm;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\Mapping\MappingException;
use Nette\Application\UI\Form;

class RecipientFormFactory extends BaseFormFactory
{

	/**
	 * @return Form
	 * @throws MappingException
	 */
	public function create()
	{
		$form = new BootstrapForm();
		$form->addHidden('id');
		$form->addEmail('email', 'E-mail')->setRequired(true);
		$form->addText('firstName', 'First Name');
		$form->addText('lastName', 'Last Name');
		$form->addMultiSelect('recipientGroups', 'Recipient Groups', $this->em->getRepository(RecipientGroup::class)->findPairs('id', 'title', ['account' => $this->account]));
		$form->addCheckbox('subscribed', 'Subscribed')->setDefaultValue(true);
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
			$recipient = $this->em->getRepository(Recipient::class)->find($values->id);
		} else {
			$recipient = new Recipient();
		}

		$recipient->setEmail($values->email);
		$recipient->setFirstName($values->firstName);
		$recipient->setLastName($values->lastName);
		$recipient->setSubscribed($values->subscribed);
		$recipient->setAccount($this->account);

		$recipientGroups = new ArrayCollection();

		foreach ($values->recipientGroups as $recipientGroupId) {
			$recipientGroup = $this->em->getRepository(RecipientGroup::class)
				->findOneBy([
					'id' => $recipientGroupId,
					'account' => $this->account,
				]);

			if ($recipientGroup) {
				$recipientGroups->add($recipientGroup);
			}
		}

		$recipient->setRecipientGroups($recipientGroups);

		try {
			$this->em->persist($recipient);
			$this->em->flush();
		} catch (Exception $e) {
			$presenter->flashError('This recipient cannot be saved.');
			$presenter->redirect('this');
		}

		$presenter->flashSuccess('Recipient saved successfully');
		$presenter->redirect('Recipient:list');
	}

}