<?php

declare(strict_types=1);

namespace BitBag\SyliusBraintreePlugin\Request;

use Payum\Core\Request\Generic;

final class ObtainCardholderAuthentication extends Generic
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
