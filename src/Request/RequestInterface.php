<?php

declare(strict_types=1);

namespace BitBag\SyliusBraintreePlugin\Request;

interface RequestInterface
{
    public function getResponse();

    public function setResponse(): void;
}
