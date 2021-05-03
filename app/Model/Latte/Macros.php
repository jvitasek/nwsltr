<?php declare(strict_types = 1);

namespace App\Model\Latte;

use JetBrains\PhpStorm\Pure;
use Latte\Compiler;
use Latte\Macros\MacroSet;

final class Macros extends MacroSet
{

	#[Pure] public static function register(Compiler $compiler): void
	{
		$compiler = new Macros($compiler);
	}

}