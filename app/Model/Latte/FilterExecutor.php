<?php declare(strict_types = 1);

namespace App\Model\Latte;

use Latte\Engine;

final class FilterExecutor
{

	/** @var Engine */
	private Engine $latte;

	/**
	 * FilterExecutor constructor.
	 *
	 * @param Engine $latte
	 */
	public function __construct(Engine $latte)
	{
		$this->latte = $latte;
	}

	/**
	 * @param string $name
	 * @param mixed[] $args
	 * @return mixed
	 */
	public function __call(string $name, array $args): mixed
	{
		return $this->latte->invokeFilter($name, $args);
	}

	public function __get(string $name): mixed
	{
		return $this->latte->invokeFilter($name, []);
	}

}