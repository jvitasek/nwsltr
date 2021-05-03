<?php declare(strict_types = 1);

namespace App\Model\Latte;

use App\Model\Security\SecurityUser;
use Nette\Bridges\ApplicationLatte\LatteFactory;
use Nette\Bridges\ApplicationLatte\Template;
use Nette\Bridges\ApplicationLatte\TemplateFactory as NetteTemplateFactory;
use Nette\Caching\Storage;
use Nette\Http\IRequest;
use Nette\Application\UI;

final class TemplateFactory extends NetteTemplateFactory
{

	/** @var SecurityUser */
	private SecurityUser $user;

	/**
	 * TemplateFactory constructor.
	 *
	 * @param LatteFactory $latteFactory
	 * @param IRequest $httpRequest
	 * @param SecurityUser $user
	 * @param Storage $cacheStorage
	 * @param string|null $templateClass
	 */
	public function __construct(
        LatteFactory $latteFactory,
        IRequest $httpRequest,
        SecurityUser $user,
        Storage $cacheStorage,
        ?string $templateClass = null
    )
	{
		parent::__construct($latteFactory, $httpRequest, $user, $cacheStorage, $templateClass);

		$this->user = $user;
	}

	public function createTemplate(?UI\Control $control = null, ?string $class = null): UI\Template
	{
		/** @var Template $template */
		$template = parent::createTemplate($control);

		// Remove default $template->user for prevent misused
		unset($template->user);

		// Assign new variables
		$template->_user = $this->user;
		$template->_template = $template;
		$template->_filters = new FilterExecutor($template->getLatte());

		return $template;
	}

}