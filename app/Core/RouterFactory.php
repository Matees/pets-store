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
//                    ->addRoute('order', ['presenter' => 'Order', 'action' => 'create', 'method' => 'POST'])
//                    ->addRoute('order/<id>', ['presenter' => 'Order', 'action' => 'detail', '_method' => 'GET'])
//                    ->addRoute('order/<id>', ['presenter' => 'Order', 'action' => 'delete', '_method' => 'DELETE'])
//                    ->addRoute('inventory', ['presenter' => 'Order', 'action' => 'inventory', '_method' => 'GET'])
                ->end()
                ->withPath('user')
//                ->addRoute('', ['presenter' => 'User', 'action' => 'create', 'method' => 'POST'])
//                ->addRoute('createWithList', ['presenter' => 'User', 'action' => 'createWithList', 'method' => 'POST'])
                ->addRoute('<name>', ['presenter' => 'User', 'action' => 'detail', 'method' => 'GET'])
//                ->addRoute('<name>', ['presenter' => 'User', 'action' => 'delete', 'method' => 'DELETE'])
//                ->addRoute('<name>', ['presenter' => 'User', 'action' => 'update', 'method' => 'PUT'])
                ->addRoute('login', ['presenter' => 'User', 'action' => 'login', 'method' => 'GET'])
                ->addRoute('logout', ['presenter' => 'User', 'action' => 'logout', 'method' => 'GET'])
                ->end()
            ->end();

        return $router;
	}
}
