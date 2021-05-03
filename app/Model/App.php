<?php declare(strict_types = 1);

namespace App\Model;

final class App
{

	public const DESTINATION_FRONT_HOMEPAGE = ':Front:Home:default';
	public const DESTINATION_SIGN_IN = ':Front:Sign:in';
	public const DESTINATION_AFTER_SIGN_IN = self::DESTINATION_FRONT_HOMEPAGE;
	public const DESTINATION_AFTER_SIGN_OUT = self::DESTINATION_SIGN_IN;

}