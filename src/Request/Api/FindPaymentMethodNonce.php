<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
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
