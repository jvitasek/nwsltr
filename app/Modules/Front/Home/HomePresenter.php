<?php declare(strict_types = 1);

namespace App\Modules\Front\Home;

use App\Domain\Helper\Statistics;
use App\Model\Session\AccountSession;
use App\Modules\Front\BaseFrontPresenter;
use Nette\DI\Attributes\Inject;
use Nette\Utils\ArrayHash;

final class HomePresenter extends BaseFrontPresenter
{

	#[Inject]
	public AccountSession $accountSession;
	#[Inject]
	public Statistics $statistics;
	private ArrayHash $homepageStats;

	public function actionDefault(): void
	{
		$statistics = new ArrayHash();
		$statistics->offsetSet('totalSent', $this->statistics->getTotalSent($this->account));
		$statistics->offsetSet('totalOpened', $this->statistics->getTotalOpened($this->account));
		$statistics->offsetSet('totalRecipients', $this->statistics->getTotalRecipients($this->account));
		$statistics->offsetSet('totalUnsubscribes', $this->statistics->getTotalUnsubscribes($this->account));
		$statistics->offsetSet('sentOpenedRationByMonths', $this->statistics->getSentOpenedRationByMonths($this->account));
		$this->homepageStats = $statistics;
	}

	public function renderDefault(): void
	{
		$this->template->statistics = $this->homepageStats;
	}

}