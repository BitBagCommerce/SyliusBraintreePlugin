<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusBraintreePlugin\Request\Api;

final class GenerateClientToken
{
    protected $customerId;

    protected $merchantAccountId;

    protected $response;

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
