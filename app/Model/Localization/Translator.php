<?php declare(strict_types = 1);

namespace App\Model\Localization;

class Translator implements \Nette\Localization\Translator
{

	public function translate($message, ...$parameters): string
	{
		return $message;
	}

}
