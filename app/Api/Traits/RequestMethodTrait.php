<?php

namespace App\Api\Traits;

use Nette\Application\UI\Presenter;
use Nette\Http\IRequest;
use Nette\Http\IResponse;

trait RequestMethodTrait
{
    public function checkRequestMethod(Presenter $presenter, string $method): void
    {
        if (!$presenter->getRequest()->isMethod($method)) {
            $presenter->error('Invalid request method. Only ' . strtoupper($method) . ' is allowed.', 405);
        }
    }
}

