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
                    ->addRoute('findByTags', 'Pet:findByTags')
                    ->addRoute('findByStatus', 'Pet:findByStatus')
                    ->addRoute('create', 'Pet:create')
                    ->addRoute('<id>/uploadImage', 'Pet:uploadImage')
                    ->addRoute('detail/<id>', 'Pet:detail')
                    ->addRoute('updateParams/<id>', 'Pet:updateWithParameters')
                    ->addRoute('delete/<id>', 'Pet:delete')
                    ->addRoute('update', 'Pet:update')
                ->end()
                ->withPath('store')
                    ->addRoute('order', ['presenter' => 'Order', 'action' => 'create'])
                    ->addRoute('inventory', ['presenter' => 'Order', 'action' => 'inventory'])
                    ->addRoute('order/detail/<id>', ['presenter' => 'Order', 'action' => 'detail'])
                    ->addRoute('order/<id>', ['presenter' => 'Order', 'action' => 'delete'])
                ->end()
                ->withPath('user')
                    ->addRoute('login', ['presenter' => 'User', 'action' => 'login'])
                    ->addRoute('logout', ['presenter' => 'User', 'action' => 'logout'])
                    ->addRoute('createWithList', ['presenter' => 'User', 'action' => 'createWithList'])
                    ->addRoute('create', ['presenter' => 'User', 'action' => 'create'])
                    ->addRoute('detail/<name>', ['presenter' => 'User', 'action' => 'detail'])
                    ->addRoute('delete/<name>', ['presenter' => 'User', 'action' => 'delete'])
                    ->addRoute('update/<name>', ['presenter' => 'User', 'action' => 'update'])
                ->end()
            ->end();

        return $router;
	}
}
