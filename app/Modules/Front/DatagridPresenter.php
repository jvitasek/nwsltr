<?php declare(strict_types = 1);

namespace App\Modules\Front;

use AlesWita\VisualPaginator\VisualPaginator;
use AlesWita\VisualPaginator\VisualPaginatorFactory;
use Doctrine\ORM\QueryBuilder;
use Nette\DI\Attributes\Inject;

abstract class DatagridPresenter extends BaseFrontPresenter
{
	const DEFAULT_ITEMS_PER_PAGE = 50;

	#[Inject]
	public VisualPaginatorFactory $visualPaginatorFactory;

	public function startup()
	{
		parent::startup();

		if (property_exists($this, 'sortField')) {
			$this->template->sortField = $this->sortField;
			$this->template->sortDirection = $this->sortDirection;
		}

		if (property_exists($this, 'filterField')) {
			$this->template->filterField = $this->filterField;
			$this->template->filterQuery = $this->filterQuery;
		}

		$this->template->datagrid = true;
	}

	public function handleSort(?string $sortField, ?string $sortDirection): void
	{
		$this->sortField = (string) $sortField;
		$this->sortDirection = (string) $sortDirection;
	}

	public function handleFilter(?string $filterField, ?string $filterQuery): void
	{
		$this->filterField = (string) $filterField;
		$this->filterQuery = (string) $filterQuery;
	}

	public function initializeQueryBuilder(string $class): QueryBuilder
	{
		$qb = $this->em->getRepository($class)->qb();

		if (property_exists($this, 'filterQuery') && $this->filterQuery && $this->filterField) {
			$qb
				->andWhere($this->filterField . ' LIKE :query')
				->setParameter('query', '%' . $this->filterQuery . '%');
		}

		if (property_exists($this, 'sortField') && $this->sortField && $this->sortDirection) {
			$qb->orderBy($this->sortField, $this->sortDirection);
		}

		return $qb;
	}

	protected function createComponentPagination(): VisualPaginator
	{
		$control = $this->visualPaginatorFactory->create();
		$control->ajax = false;
		$control->canSetItemsPerPage = true;
		$control->itemsPerPageList = [self::DEFAULT_ITEMS_PER_PAGE => self::DEFAULT_ITEMS_PER_PAGE];
		$control->setItemsPerPage(self::DEFAULT_ITEMS_PER_PAGE);
		$control->templateFile = MODULES_DIR . '/Base/templates/@pagination.latte';

		return $control;
	}

	public function handleDelete(string $entityId, string $entity): void
	{
		$this->em->getRepository($entity)->delete((int) $entityId);

		if ($this->isAjax()) {
			$this->redrawControl('grid');
			$this->redrawControl('flashes');
		} else {
			$this->flashSuccess($this->translator->translate('Record deleted successfully'));
			$this->redirect('this');
		}
	}

	public function handleOpenModal(string $type, ?int $entityId = null, ?string $entity = null): void
	{
		$this->template->openModal = true;
		$this->template->modalType = $type;
		$this->template->entity = $entity;
		$this->template->entityId = $entityId;
		$this->template->modalContent = UI_DIR . '/Modals/' . $type . '.latte';
		$this->template->userEntity = $this->userEntity;
		$this->template->model = $this->em->getRepository($entity)->find($entityId);

		if ($type === 'importXls') {
			$this['importXlsForm']->setDefaults([
				'id' => $entityId,
			]);
		} elseif ($type === 'sendTest') {
			$this['sendTestForm']->setDefaults([
				'id' => $entityId,
			]);
		}

		$this->redrawControl('modal');
		$this->redrawControl('modalContent');
	}

	public function handleToggleField(?string $recordId, ?string $field, ?string $checked, ?string $entity): void
	{
		$record = $this->findRecord($field, $entity, (int) $recordId);
		$checked = ($checked === 'true');
		$record->{'set' . $field}($checked);
		$this->em->persist($record);
		$this->em->flush();
		$this->redirect('this');
	}

	public function handleChangeStatus(?string $field, ?string $value, ?string $entity, ?string $recordId): void
	{
		$record = $this->findRecord($field, $entity, (int) $recordId);
		$record->{'set' . $field}((int) $value);
		$this->em->persist($record);
		$this->em->flush();
		$this->redirect('this');
	}

	private function findRecord(string $field, string $entity, int $recordId): ?object
	{
		$recordId = (int) $recordId;
		$repository = $this->em->getRepository($entity);

		if ($repository !== null) {
			$record = $repository->find($recordId);

			if ($record) {
				$method = 'set' . $field;

				if (method_exists($record, $method)) {
					return $record;
				}
			}
		}

		return null;
	}

}
