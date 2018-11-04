<?php

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
