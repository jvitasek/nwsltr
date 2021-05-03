<?php declare(strict_types = 1);

namespace App\Modules\Admin;

use App\Modules\Front\DatagridPresenter;

abstract class BaseAdminPresenter extends DatagridPresenter
{

	public function startup()
	{
		parent::startup();

		if (!$this->userEntity->isAdmin()) {
			if (
                !(
                    $this->getPresenter()->getName() === 'Admin:User'
                    && $this->getPresenter()->getAction() === 'form'
                    && ((int) $this->getParameter('id') === $this->userEntity->getId())
                )
            ) {
				$this->flashWarning('You are not authorized to view this section');
				$this->redirect(':Front:Home:default');
			}
		}
	}

}