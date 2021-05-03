<?php declare(strict_types = 1);

namespace App\Modules\Api\RecipientGroup;

use App\Model\Database\Entity\Account;
use App\Model\Database\Entity\Mailing;
use App\Model\Database\Entity\RecipientGroup;
use App\Modules\Api\BaseApiPresenter;
use DateTime;
use Nette\Application\BadRequestException;
use Nette\Application\Responses\JsonResponse;
use Tracy\Debugger;

final class RecipientGroupPresenter extends BaseApiPresenter
{

	public function actionRead(string $id): void
	{
		/** @var Mailing|null $mailing */
		$mailing = $this->em->getRepository(Mailing::class)->find($id);

		if ($mailing !== null) {
			$result = [];
			/** @var Account|null $account */
			$account = $mailing->getAccount();

			if ($account !== null) {
				$recipientGroups = $this->em->getRepository(RecipientGroup::class)->findBy(['account' => $account]);

				/** @var RecipientGroup $recipientGroup */
				foreach ($recipientGroups as $recipientGroup) {
					$result[] = ['id' => $recipientGroup->getId(), 'title' => $recipientGroup->getTitle()];
				}

				$this->sendResponse(new JsonResponse($result));
			} else {
				throw new BadRequestException();
			}
		} else {
			throw new BadRequestException();
		}
	}

}