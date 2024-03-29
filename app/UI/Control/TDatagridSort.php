<?php declare(strict_types = 1);

namespace App\UI\Control;

use App\Modules\Base\BasePresenter;
use App\Modules\Front\DatagridPresenter;
use Nette\Application\Attributes\Persistent;
use stdClass;

/** @mixin DatagridPresenter */
trait TDatagridSort
{

	#[Persistent]
	public string $sortDirection = '';

	#[Persistent]
	public string $sortField = '';

}