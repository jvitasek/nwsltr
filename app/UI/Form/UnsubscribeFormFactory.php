<?php declare(strict_types = 1);

namespace App\UI\Form;

use App\Model\App;
use App\Model\Database\Entity\Unsubscribe;
use App\Model\Database\EntityManager;
use App\Model\Exception\Runtime\AuthenticationException;
use App\Model\Session\AccountSession;
use App\Modules\Front\Sign\SignPresenter;
use Nette\Application\UI\Form;

class UnsubscribeFormFactory extends BaseFormFactory
{

	public function __construct(EntityManager $entityManager, AccountSession $accountSession)
	{
		parent::__construct($entityManager, $accountSession);
	}

	/** @return Form */
	public function create()
	{
		$form = new Form();
		$form->addHidden('unsubscribeId')->setRequired();
		$form->addTextArea('feedback', 'Zpětná vazba');
		$form->addSubmit('unsubscribe');
		$form->addSubmit('resubscribe');
		$form->onSuccess[] = [$this, 'success'];

		return $form;
	}

	public function success(Form $form): void
	{
		$values = $form->getValues();
		/** @var SignPresenter $presenter */
		$presenter = $form->getPresenter();

		/** @var Unsubscribe $unsubscribe */
		$unsubscribe = $this->em->getRepository(Unsubscribe::class)->find($values->unsubscribeId);

		if ($unsubscribe !== null) {
			$unsubscribe->setNote($values->feedback);
			$this->em->persist($unsubscribe);
			$this->em->flush();
		}
	}

}