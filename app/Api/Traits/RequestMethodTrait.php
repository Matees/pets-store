<?php

namespace App\Api\Traits;

use Nette\Application\UI\Presenter;

trait RequestMethodTrait
{
    public function checkRequestMethod(Presenter $presenter, string $method): void
    {
        if (!$presenter->getRequest()->isMethod($method)) {
            $presenter->error('Invalid request method. Only ' . strtoupper($method) . ' is allowed.', 405);
        }
    }
}

