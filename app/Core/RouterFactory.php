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
        $router
            ->withModule('Api')
                ->withPath('pet')
                    ->addRoute('', [
                        'presenter' => 'Pet',
                        'action' => 'create',
                        'method' => 'post',
                    ])
                ->end()
            ->end();

        return $router;
	}
}
