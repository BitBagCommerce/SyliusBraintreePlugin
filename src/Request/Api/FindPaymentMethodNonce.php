<?php

declare(strict_types=1);

namespace BitBag\SyliusBraintreePlugin\Request\Api;

use Braintree\PaymentMethodNonce;

final class FindPaymentMethodNonce
{
    private $nonceString;

    private $response;

    public function __construct(string $nonceString)
    {
        $this->nonceString = $nonceString;
    }

    public function getNonceString(): string
    {
        return $this->nonceString;
    }

    public function getResponse(): PaymentMethodNonce
    {
        return $this->response;
    }

    public function setResponse(PaymentMethodNonce $response): void
    {
        $this->response = $response;
    }
}
