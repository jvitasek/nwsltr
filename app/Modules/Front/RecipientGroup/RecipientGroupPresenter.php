<?php declare(strict_types = 1);

namespace App\Modules\Front\RecipientGroup;

use App\Model\Database\Entity\RecipientGroup;
use App\Modules\Front\DatagridPresenter;
use App\UI\Form\ImportXlsFormFactory;
use App\UI\Form\RecipientGroupFormFactory;
use Contributte\FormsBootstrap\BootstrapForm;
use Nette\Application\BadRequestException;
use Nette\Application\UI\Form;
use Nette\DI\Attributes\Inject;

final class RecipientGroupPresenter extends DatagridPresenter
{

    #[Inject]
    public RecipientGroupFormFactory $recipientGroupFormFactory;
    public ImportXlsFormFactory $importXlsFormFactory;
	private array $recipientGroups;
	private ?RecipientGroup $recipientGroup = null;

	public function actionList(): void
	{
		$qb = $this->initializeQueryBuilder(RecipientGroup::class);
		$qb->andWhere('p.account = :selectedAccount')->setParameter('selectedAccount', $this->account);
		$this->recipientGroups = $qb->getQuery()->getResult();
	}

	public function renderList(): void
	{
		$this->template->recipientGroups = $this->recipientGroups;
	}

	public function actionForm(?int $id = null): void
	{
		if ($id) {
			/** @var RecipientGroup|null $recipientGroup */
			$recipientGroup = $this->em->getRepository(RecipientGroup::class)->findOneBy([
				'id' => $id,
				'account' => $this->account,
			]);

			if ($recipientGroup !== null) {
				$this->recipientGroup = $recipientGroup;

				$this['recipientGroupForm']->setDefaults([
					'id' => $this->recipientGroup->getId(),
					'title' => $this->recipientGroup->getTitle(),
				]);
			} else {
				throw new BadRequestException();
			}
		}
	}

	public function renderForm(): void
	{
		$this->template->recipientGroup = $this->recipientGroup;
	}

	protected function createComponentRecipientGroupForm(): Form
	{
		return $this->recipientGroupFormFactory->create();
	}

	protected function createComponentImportXlsForm(): Form|BootstrapForm
	{
		return $this->importXlsFormFactory->create();
	}

}