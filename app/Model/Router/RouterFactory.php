<?php declare(strict_types = 1);

namespace App\Model\Router;

use Contributte\ApiRouter\ApiRoute;
use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;

final class RouterFactory
{

	public function create(): RouteList
	{
		$router = new RouteList();
		$this->buildApi($router);
		$this->buildAdmin($router);
		$this->buildFront($router);

		return $router;
	}

	protected function buildFront(RouteList $router): RouteList
	{
		$router[] = $list = new RouteList('Front');
		$list[] = new Route('/unsubscribe/<queueHash>/<mailingId>/<unsubscribeId>', 'Unsubscribe:default');
		$list[] = new Route('//%host%/[<locale=en cs|en>/]<presenter>/<action>[/<id>]', 'Home:default');

		return $router;
	}

	protected function buildAdmin(RouteList $router): RouteList
	{
		$router[] = $list = new RouteList('Admin');
		$list[] = new Route('//%host%/[<locale=en cs|en>/]admin/<presenter>/<action>[/<id>]');

		return $router;
	}

	protected function buildApi(RouteList $router): RouteList
	{
		$router[] = $list = new RouteList('Api');

		$list[] = new ApiRoute('/api/editor/save', 'Editor', [
			'methods' => ['POST'],
		]);

		$list[] = new ApiRoute('/api/element/save', 'Element', [
			'methods' => ['POST'],
		]);

		$list[] = new ApiRoute('/api/image/save', 'Image', [
			'methods' => ['POST'],
		]);

		$list[] = new ApiRoute('/api/conversion/save/<queueHash>/<elementId>', 'Conversion', [
			'methods' => ['GET'],
		]);

		$list[] = new ApiRoute('/api/queue/read/<queueHash>/<mailingId>', 'Queue', [
			'methods' => ['GET'],
		]);

		$list[] = new ApiRoute('/api/unsubscribe/<queueHash>/<mailingId>', 'Unsubscribe', [
			'methods' => ['GET'],
		]);

		$list[] = new ApiRoute('/api/editor/read/<id>', 'Editor', [
			'parameters' => [
				'id' => ['requirement' => '\d+'],
			],
		]);
		$list[] = new ApiRoute('/api/recipient-group/read/<id>', 'RecipientGroup', [
			'parameters' => [
				'id' => ['requirement' => '\d+'],
			],
		]);
		$list[] = new ApiRoute('/api/selected-recipient-group/read/<id>', 'SelectedRecipientGroup', [
			'parameters' => [
				'id' => ['requirement' => '\d+'],
			],
		]);

		return $router;
	}

}
