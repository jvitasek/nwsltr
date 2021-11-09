<?php declare(strict_types = 1);

namespace App\Modules\Api\Editor;

use App\Model\Database\Entity\Element;
use App\Model\Database\Entity\Mailing;
use App\Model\Database\Entity\RecipientGroup;
use App\Model\Database\Repository\ElementRepository;
use App\Modules\Api\BaseApiPresenter;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Nette\Application\BadRequestException;
use Nette\Utils\Validators;
use Tracy\Debugger;

final class EditorPresenter extends BaseApiPresenter
{

	public function actionCreate(): void
	{
		$post = json_decode($this->getHttpRequest()->getRawBody(), true);

		if ($post && is_array($post)) {
			/** @var Mailing|null $mailing */
			$mailing = $this->em->getRepository(Mailing::class)->find((int) $post['id']);

			if ($mailing !== null) {
				$mailing->setTitle((string) $post['meta']['title']);
				$mailing->setSubject((string) $post['meta']['subject']);
				$emailFrom = trim($post['meta']['emailFrom']);
				if (Validators::isEmail($emailFrom)) {
					$mailing->setEmailFrom($emailFrom);
				}
				$mailing->setSendDate(new DateTime($post['meta']['date']));

				$mailing->setJsonData(json_encode($post));

				$mailing->setRecipientGroups(new ArrayCollection());

				foreach ($post['recipientGroups'] as $recipientGroupId) {
					/** @var RecipientGroup|null $recipientGroup */
					$recipientGroup = $this->em->getRepository(RecipientGroup::class)->find($recipientGroupId);

					if ($recipientGroup !== null && !$mailing->getRecipientGroups()->contains($recipientGroup)) {
						$mailing->addRecipientGroup($recipientGroup);
					}
				}

				$this->em->persist($mailing);
				$this->em->flush();

				foreach ($post['body'] as $component) {
					if ($component['component'] === 'Button') {
						/** @var ElementRepository $elementRepository */
						$elementRepository = $this->em->getRepository(Element::class);
						$elementRepository->fromButton($component);
					}
				}

				$this->getHttpResponse()->setCode(200);
				$this->sendJson(['result' => 'ok']);
			}
		}

		$this->getHttpResponse()->setCode(404);
		$this->sendJson(['result' => 'nok']);
	}

	public function actionRead(string $id): void
	{
		/** @var Mailing|null $mailing */
		$mailing = $this->em->getRepository(Mailing::class)->find($id);

		if ($mailing !== null) {
			$this->sendJson($mailing->getJsonFormatted());
		} else {
			throw new BadRequestException();
		}
	}

}
