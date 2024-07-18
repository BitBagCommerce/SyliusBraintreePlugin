<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusBraintreePlugin\Request\Api;

final class GenerateClientToken
{
    private $customerId;

    private $merchantAccountId;

    private $response;

    public function getCustomerId()
    {
        return $this->customerId;
    }

    public function setCustomerId($value): void
    {
        $this->customerId = $value;
    }

    public function getMerchantAccountId()
    {
        return $this->merchantAccountId;
    }

    public function setMerchantAccountId($value): void
    {
        $this->merchantAccountId = $value;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function setResponse($value): void
    {
        $this->response = $value;
    }
}
