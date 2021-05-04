<?php declare(strict_types = 1);

namespace App\Modules\Api\Image;

use App\Domain\Helper\FileUpload;
use App\Modules\Api\BaseApiPresenter;
use Nette\DI\Attributes\Inject;
use Nette\Utils\FileSystem;
use Nette\Utils\Image;
use Nette\Utils\ImageException;
use Nette\Utils\Random;
use Nette\Utils\Strings;
use Tracy\Debugger;

final class ImagePresenter extends BaseApiPresenter
{

	public const SUPPORTED_BASE64_TYPES = [
		'data:image/jpeg;base64,',
		'data:image/png;base64,',
	];

	public FileUpload $fileUpload;

	public function actionCreate(): void
	{
		$post = json_decode($this->getHttpRequest()->getRawBody(), true);

		$savedImages = [];

		foreach ($post as $imagePost) {
			if (isset($imagePost['base64'])) {
				$imageBase = $imagePost['base64'];

				$image = Image::fromString(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imageBase)));
				$savedImage = $this->fileUpload->saveImage($image);

				if ($savedImage) {
					$savedImages[] = $savedImage;
				}
			}
		}

		$this->getHttpResponse()->setCode(200);
		$this->sendJson(['result' => 'ok', 'image' => $savedImages]);
	}

}