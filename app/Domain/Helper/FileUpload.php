<?php declare(strict_types = 1);

namespace App\Domain\Helper;

use Nette\Utils\FileSystem;
use Nette\Utils\Image;
use Nette\Utils\ImageException;
use Nette\Utils\Random;
use Tracy\Debugger;

final class FileUpload
{

	public function saveImage(Image $image): ?string
	{
		if (!is_dir(DATA_DIR)) {
			FileSystem::createDir(DATA_DIR);
		}

		$path = DATA_DIR . '/mailings/';

		if (!is_dir($path)) {
			FileSystem::createDir($path);
		}

		$filename = Random::generate(20) . '.jpg';

		try {
			$finalPath = $path . $filename;
			$image->save($finalPath, 100, Image::JPEG);

			return '/uploads/mailings/' . $filename;
		} catch (ImageException $e) {
			Debugger::log($e->getMessage(), 'image');
		}

		return null;
	}

}