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
//                    ->addRoute('<id>', 'Pet:detail')
                    ->addRoute('findByStatus', 'Pet:findByStatus')
                    ->addRoute('findByTags', 'Pet:findByTags')
//                    ->addRoute('', 'Pet:create')
//                    ->addRoute('', 'Pet:update')
//                    ->addRoute('<id>', 'Pet:updateWithParameters')
//                    ->addRoute('<id>', 'Pet:delete')
                    ->addRoute('<id>/uploadImage', 'Pet:uploadImage')
                ->end()
            ->end();

        return $router;
	}
}
