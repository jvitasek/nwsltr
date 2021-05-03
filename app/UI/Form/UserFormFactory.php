<?php declare(strict_types = 1);

namespace App\UI\Form;

use App\Model\Database\Entity\Account;
use App\Model\Database\Entity\User;
use App\Model\Database\EntityManager;
use App\Model\Session\AccountSession;
use App\Modules\Base\BasePresenter;
use Contributte\FormsBootstrap\BootstrapForm;
use Doctrine\Common\Collections\ArrayCollection;
use JetBrains\PhpStorm\NoReturn;
use Nette\Application\UI\Form;
use Nette\Http\FileUpload;
use Nette\Utils\FileSystem;
use Nette\Utils\Random;

class UserFormFactory extends BaseFormFactory
{

	private \Nette\Security\User $user;

	public function __construct(
		EntityManager $entityManager,
		\Nette\Security\User $user,
		AccountSession $accountSession
	)
	{
		parent::__construct($entityManager, $accountSession);

		$this->user = $user;
	}

	public function create(): Form|BootstrapForm
	{
		/** @var User $userEntity */
		$userEntity = $this->em->getRepository(User::class)->find($this->user->getId());

		$form = new BootstrapForm();
		$form->addHidden('id');
		$form->addText('firstName', 'First Name')->setRequired();
		$form->addText('lastName', 'Last Name')->setRequired();
		$form->addEmail('email', 'E-mail')->setRequired();
		$form->addPassword('password', 'Password');

		if ($userEntity->isAdmin()) {
			$form->addSelect('status', 'Status', User::STATES)->setRequired();
			$form->addSelect('role', 'Role', User::ROLES)->setRequired();
			$form->addMultiSelect('accounts', 'Accounts', $this->em->getRepository(Account::class)->findPairs('id', 'title', ['active' => true]));
		}

		$form->addSubmit('submit', 'Submit');
		$form->onSuccess[] = [$this, 'success'];

		return $form;
	}

	#[NoReturn] public function success(Form $form): void
	{
		$values = $form->getValues();
		/** @var BasePresenter $presenter */
		$presenter = $form->getPresenter();

		/** @var User $userEntity */
		$userEntity = $this->em->getRepository(User::class)->find($this->user->getId());

		if ($values->id) {
			$user = $this->em->getRepository(User::class)->find((int) $values->id);
		} else {
			$user = new User();
		}

		$user->setFirstName($values->firstName);
		$user->setLastName($values->lastName);
		$user->setEmail($values->email);

		if ($values->password) {
			$user->setPassword($values->password);
		}

		if ($userEntity->isAdmin()) {
			$user->setAccounts(new ArrayCollection());

			foreach ($values->accounts as $accountId) {
				/** @var Account $account */
				$account = $this->em->getRepository(Account::class)->findOneBy([
					'id' => $accountId,
					'active' => true,
				]);

				if ($account !== null) {
					if (!$user->getAccounts()->contains($account)) {
						$user->addAccount($account);
					}
				}
			}

			$user->setRole($values->role);
			$user->setState($values->status);
		}

		$this->em->persist($user);
		$this->em->flush();

		$presenter->flashSuccess('User saved successfully');

		if($userEntity->isAdmin()) {
			$presenter->redirect('User:list');
		} else {
			$presenter->redirect(':Front:Home:default');
		}
	}

}