<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

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
