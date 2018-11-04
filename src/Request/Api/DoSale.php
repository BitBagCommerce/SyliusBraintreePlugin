<?php

declare(strict_types=1);

namespace BitBag\SyliusBraintreePlugin\Request\Api;

use Payum\Core\Request\Generic;

final class DoSale extends Generic
{
    protected $response;

    public function getResponse()
    {
        return $this->response;
    }

    public function setResponse($value): void
    {
        $this->response = $value;
    }
}
