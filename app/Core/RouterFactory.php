<?php

declare(strict_types=1);

namespace App\Core;

use Nette;
use Nette\Application\Routers\RouteList;
use Nette\Routing\Route;


final class RouterFactory
{
	use Nette\StaticClass;

	public static function createRouter(): RouteList
	{
		$router = new RouteList;
        $router->addRoute('<presenter>/<action>[/<id>]', 'Home:default');
        $router->addRoute('api/<presenter>/[<action>]', 'Pet:create');

        return $router;
	}
}
