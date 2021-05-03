<?php declare(strict_types = 1);

namespace App\Model\Utils;

use JetBrains\PhpStorm\Pure;

final class GraphColors
{

	private const COLORS
		= [
			'#3e95cd',
			'#8e5ea2',
			'#3cba9f',
			'#e8c3b9',
			'#c45850',
			'#e74c3c',
			'#95a5a6',
			'#36ae5f',
		];

	#[Pure] public static function get(int $index): string
	{
		if (isset(self::COLORS[$index])) {
			return self::COLORS[$index];
		}

		return sprintf('#%06X', mt_rand(0, 0xFFFFFF));
	}

}