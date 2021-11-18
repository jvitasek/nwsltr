<?php

namespace App\Model\Localization;

use Contributte;
use Contributte\Translation\LocalesResolvers\ResolverInterface;
use Nette;

class LanguageResolver implements ResolverInterface
{

	use Nette\SmartObject;

	public static string $parameter = 'locale';
	private Nette\Http\IRequest $request;

	public function __construct(Nette\Http\IRequest $request)
	{
		$this->request = $request;
	}

	public function resolve(Contributte\Translation\Translator $translator): ?string
	{
		$path = $this->request->getUrl()->getPath();
		$exploded = explode('/', $path);

		if (isset($exploded[1])) {
			if ($exploded[1] === 'cs') {
				return 'cs';
			}
		}

		return 'en';
	}

}
