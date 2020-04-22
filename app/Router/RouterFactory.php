<?php

declare(strict_types=1);

namespace App\Router;

use Nette;
use Nette\Application\Routers\RouteList;


final class RouterFactory
{
	use Nette\StaticClass;

	public static function createRouter(): RouteList
	{
		$router = new RouteList;
		$router->addRoute('/admin/<presenter>/<action>[/<id>]', [
			'module' => 'Admin',
			'presenter' => 'Homepage',
			'action' => 'default',
		]);
		$router->addRoute('<presenter>/<action>[/<id>]', 'Front:Homepage:default');

		return $router;
	}
}
