<?php declare(strict_types = 1);

namespace App\Modules\Api\SelectedRecipientGroup;

use App\Model\Database\Entity\Mailing;
use App\Model\Database\Entity\RecipientGroup;
use App\Modules\Api\BaseApiPresenter;
use Nette\Application\BadRequestException;
use Nette\Application\Responses\JsonResponse;

final class SelectedRecipientGroupPresenter extends BaseApiPresenter
{

	public function actionRead(string $id): void
	{
		/** @var Mailing|null $mailing */
		$mailing = $this->em->getRepository(Mailing::class)->find($id);

		if ($mailing !== null) {
			$result = [];

			/** @var RecipientGroup $recipientGroup */
			foreach ($mailing->getRecipientGroups() as $recipientGroup) {
				$result[] = ['id' => $recipientGroup->getId(), 'title' => $recipientGroup->getTitle()];
			}

			$this->sendResponse(new JsonResponse($result));
		} else {
			throw new BadRequestException();
		}
	}

}