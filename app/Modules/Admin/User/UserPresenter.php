<?php declare(strict_types = 1);

namespace App\Modules\Admin\User;

use App\Model\Database\Entity\User;
use App\Modules\Admin\BaseAdminPresenter;
use App\UI\Form\UserFormFactory;
use Contributte\FormsBootstrap\BootstrapForm;
use Nette\Application\BadRequestException;
use Nette\Application\UI\Form;
use Nette\DI\Attributes\Inject;

final class UserPresenter extends BaseAdminPresenter
{

	#[Inject]
	public UserFormFactory $userFormFactory;
	private array $users;

	public function actionList(): void
	{
		$this->users = $this->em
			->getRepository(User::class)
			->findAll();
	}

	public function renderList(): void
	{
		$this->template->users = $this->users;
	}

	public function actionForm(?int $id = null): void
	{
		if ($id) {
			/** @var User|null $user */
			$user = $this->em->getRepository(User::class)->find($id);

			if ($user !== null) {
				$this['userForm']->setDefaults([
					'id' => $user->getId(),
					'firstName' => $user->getFirstName(),
					'lastName' => $user->getLastName(),
					'status' => $user->getState(),
					'email' => $user->getEmail(),
					'role' => $user->getRole(),
					'accounts' => $user->getAccounts()->map(function ($obj) {
						return $obj->getId();
					})->getValues(),
				]);
			} else {
				throw new BadRequestException();
			}
		}
	}

	/** @return Form|BootstrapForm */
	protected function createComponentUserForm(): Form|BootstrapForm
	{
		return $this->userFormFactory->create();
	}

}