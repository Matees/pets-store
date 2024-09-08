<?php

declare(strict_types=1);

namespace App\Core;

use Nette;
use Nette\Application\Routers\RouteList;


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
//                    ->addRoute('findByStatus', 'Pet:findByStatus')
//                    ->addRoute('findByTags', 'Pet:findByTags')
//                    ->addRoute('', 'Pet:create')
//                    ->addRoute('', 'Pet:update')
//                    ->addRoute('<id>', 'Pet:updateWithParameters')
//                    ->addRoute('<id>', 'Pet:delete')
//                    ->addRoute('<id>/uploadImage', 'Pet:uploadImage')
                ->end()
                ->withPath('store')
                    ->addRoute('order', ['presenter' => 'Order', 'action' => 'create', 'method' => 'POST'])
//                    ->addRoute('order/<id>', ['presenter' => 'Order', 'action' => 'detail', '_method' => 'GET'])
//                    ->addRoute('order/<id>', ['presenter' => 'Order', 'action' => 'delete', '_method' => 'DELETE'])
                    ->addRoute('inventory', ['presenter' => 'Order', 'action' => 'inventory', '_method' => 'GET'])
//                ->end()
            ->end();

        return $router;
	}
}
