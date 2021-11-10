<?php declare(strict_types = 1);

namespace App\Modules\Front\Recipient;

use App\Model\Database\Entity\Recipient;
use App\Modules\Front\DatagridPresenter;
use App\UI\Form\RecipientFormFactory;
use Nette\Application\BadRequestException;
use Nette\Application\UI\Form;
use Nette\DI\Attributes\Inject;

final class RecipientPresenter extends DatagridPresenter
{

	#[Inject]
	public RecipientFormFactory $recipientFormFactory;
	private array $recipients;
	private ?Recipient $recipient = null;

	public function actionList(): void
	{
		$qb = $this->initializeQueryBuilder(Recipient::class);
		$qb
			->andWhere('p.account = :selectedAccount')
			->setParameter('selectedAccount', $this->account);

		$paginator = $this['pagination']->getPaginator();
		$paginator->itemsPerPage = self::DEFAULT_ITEMS_PER_PAGE;
		$paginator->itemCount = count($qb->getQuery()->getResult());
		$qb->setMaxResults($paginator->itemsPerPage)->setFirstResult($paginator->offset);

		$this->recipients = $qb->getQuery()->getResult();
	}

	public function renderList(): void
	{
		$this->template->recipients = $this->recipients;
	}

	public function actionForm(?int $id = null): void
	{
		if ($id) {
			/** @var Recipient|null $recipient */
			$recipient = $this->em->getRepository(Recipient::class)->findOneBy([
				'id' => $id,
				'account' => $this->account,
			]);

			if ($recipient !== null) {
				$this->recipient = $recipient;
				$this['recipientForm']->setDefaults([
					'id' => $this->recipient->getId(),
					'email' => $this->recipient->getEmail(),
					'firstName' => $this->recipient->getFirstName(),
					'lastName' => $this->recipient->getLastName(),
					'subscribed' => $this->recipient->isSubscribed(),
					'recipientGroups' => $this->recipient->getRecipientGroups()->map(function ($obj) {
						return $obj->getId();
					})->getValues(),
				]);
			} else {
				throw new BadRequestException();
			}
		}
	}

	public function renderForm(): void
	{
		$this->template->recipient = $this->recipient;
	}

	protected function createComponentRecipientForm(): Form
	{
		return $this->recipientFormFactory->create();
	}

}
