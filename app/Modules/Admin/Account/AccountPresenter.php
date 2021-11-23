<?php declare(strict_types = 1);

namespace App\Modules\Admin\Account;

use App\Model\Database\Entity\Account;
use App\Modules\Admin\BaseAdminPresenter;
use App\UI\Form\AccountFormFactory;
use Contributte\FormsBootstrap\BootstrapForm;
use Nette\Application\BadRequestException;
use Nette\Application\UI\Form;
use Nette\DI\Attributes\Inject;
use Nette\Utils\FileSystem;

final class AccountPresenter extends BaseAdminPresenter
{

	#[Inject]
	public AccountFormFactory $accountFormFactory;
	private array $accounts;
	private ?Account $accountEdit = null;

	public function actionList(): void
	{
		$this->accounts = $this->em
			->getRepository(Account::class)
			->findAll();
	}

	public function renderList(): void
	{
		$this->template->accounts = $this->accounts;
	}

	public function actionForm(?int $id = null): void
	{
		if ($id) {
			/** @var Account|null $account */
			$account = $this->em->getRepository(Account::class)->find($id);

			if ($account !== null) {
				$this['accountForm']->setDefaults([
					'id' => $account->getId(),
					'title' => $account->getTitle(),
					'emailFrom' => $account->getEmailFrom(),
					'emailFromTitle' => $account->getEmailFromTitle(),
					'emailReplyTo' => $account->getEmailReplyTo(),
					'websiteUrl' => $account->getWebsiteUrl(),
					'unsubscribeRedirectUrl' => $account->getUnsubscribeRedirectUrl(),
					'showUnsubscribeFeedbackForm' => $account->isShowUnsubscribeFeedbackForm(),
					'showResubscribeButton' => $account->isShowResubscribeButton(),
					'primaryColorHex' => $account->getPrimaryColorHex(),
					'secondaryColorHex' => $account->getSecondaryColorHex(),
					'smtpHost' => $account->getSmtpHost(),
					'smtpUsername' => $account->getSmtpUsername(),
					'smtpPassword' => $account->getSmtpPassword(),
					'smtpSecure' => $account->getSmtpSecure(),
				]);
				$this->accountEdit = $account;
			} else {
				throw new BadRequestException();
			}
		}
	}

	public function renderForm(): void
	{
		$this->template->accountEdit = $this->accountEdit;
	}

	public function handleDeleteLogo(string $accountId): void
	{
		/** @var Account|null $account */
		$account = $this->em->getRepository(Account::class)->find($accountId);

		if ($account !== null && $this->userEntity->getAccounts()->contains($account)) {
			FileSystem::delete(WWW_DIR . $account->getLogo());
			$account->setLogo(null);
			$this->em->persist($account);
			$this->em->flush();

			$this->flashSuccess($this->translator->translate('Logo successfully removed'));
			$this->redirect('this');
		}

		$this->flashWarning($this->translator->translate('You are not authorized to do this'));
		$this->redirect('this');
	}

	/** @return Form|BootstrapForm */
	protected function createComponentAccountForm(): Form|BootstrapForm
	{
		return $this->accountFormFactory->create();
	}

}
