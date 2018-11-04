<?php

declare(strict_types=1);

namespace BitBag\SyliusBraintreePlugin\Action\Api;

use BitBag\SyliusBraintreePlugin\Request\Api\GenerateClientToken;
use Payum\Core\Exception\RequestNotSupportedException;

final class GenerateClientTokenAction extends BaseApiAwareAction
{
    public function execute($request): void
    {
        /** @var $request GenerateClientToken */
        RequestNotSupportedException::assertSupports($this, $request);

        $requestParams = [];

        $requestCustomerId = $request->getCustomerId();
        $requestMerchantAccountId = $request->getMerchantAccountId();

        if (null != $requestCustomerId) {
            $requestParams['customerId'] = $requestCustomerId;
        }

        if (null != $requestMerchantAccountId) {
            $requestParams['merchantAccountId'] = $requestMerchantAccountId;
        }

        $request->setResponse($this->api->generateClientToken($requestParams));
    }

    public function supports($request): bool
    {
        return $request instanceof GenerateClientToken;
    }
}
