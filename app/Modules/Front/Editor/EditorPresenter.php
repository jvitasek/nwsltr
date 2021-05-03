<?php declare(strict_types = 1);

namespace App\Modules\Front\Editor;

use App\Model\Database\Entity\Mailing;
use App\Model\Database\Entity\RecipientGroup;
use App\Modules\Front\BaseFrontPresenter;
use Nette\Application\BadRequestException;
use Nette\Application\LinkGenerator;
use Nette\DI\Attributes\Inject;
use Nette\Utils\Random;

final class EditorPresenter extends BaseFrontPresenter
{

    #[Inject]
    public LinkGenerator $linkGenerator;

	private Mailing $mailing;

	public function actionDefault(?int $id = null): void
	{
		if ($id) {
			/** @var Mailing|null $mailing */
			$mailing = $this->em->getRepository(Mailing::class)->findOneBy([
				'id' => $id,
				'account' => $this->account,
			]);

			if ($mailing !== null) {
				$this->mailing = $mailing;
			} else {
				throw new BadRequestException();
			}
		} else {
			$mailing = new Mailing();
			$mailing->setUser($this->userEntity);
			$mailing->setAccount($this->account);
			$mailing->setTitle('');
			$this->em->persist($mailing);
			$this->em->flush();

			$this->redirect('Editor:default', $mailing->getId());
		}
	}

	public function renderDefault(): void
	{
		$this->template->mailing = $this->mailing;
		$this->template->recipientGroups = $this->em->getRepository(RecipientGroup::class)
			->findBy([
				'account' => $this->account,
			], ['title' => 'ASC']);
		$this->template->selectedRecipientGroupsIds = $this->mailing->getRecipientGroups()->toArray();
	}

	public function actionPreview(int $id): void
	{
		/** @var Mailing|null $mailing */
		$mailing = $this->em->getRepository(Mailing::class)->findOneBy([
			'id' => $id,
			'account' => $this->account,
		]);

		if ($mailing !== null) {
			$this->mailing = $mailing;
		} else {
			throw new BadRequestException();
		}
	}

	public function renderPreview(): void
	{
		$this->template->mailing = $this->mailing;
		$this->template->html = $this->mailing->getHtml($this->linkGenerator);
	}

}