<?php declare(strict_types = 1);

namespace App\Modules\Front\Mailing;

use App\Model\Database\Entity\Mailing;
use App\Modules\Front\DatagridPresenter;
use App\UI\Control\TDatagridFilters;
use App\UI\Control\TDatagridSort;
use App\UI\Form\SendTestFormFactory;
use Nette\Application\Attributes\Persistent;
use Nette\Application\UI\Form;
use Nette\DI\Attributes\Inject;
use Tracy\Debugger;

final class MailingPresenter extends DatagridPresenter
{

	use TDatagridFilters;
	use TDatagridSort;

    #[Inject]
    public SendTestFormFactory $sendTestFormFactory;
	private array $mailings;

	public function actionList(): void
	{
		$qb = $this->initializeQueryBuilder(Mailing::class);
		$qb->andWhere('p.account = :selectedAccount')->setParameter('selectedAccount', $this->account);
		$this->mailings = $qb->getQuery()->getResult();
	}

	public function renderList(): void
	{
		$this->template->mailings = $this->mailings;
	}

	protected function createComponentSendTestForm(): Form
	{
		return $this->sendTestFormFactory->create();
	}

}
