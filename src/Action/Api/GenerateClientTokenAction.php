<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

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
