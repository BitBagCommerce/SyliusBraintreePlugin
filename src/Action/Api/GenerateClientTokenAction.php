<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusBraintreePlugin\Action\Api;

use BitBag\SyliusBraintreePlugin\Request\Api\GenerateClientToken;
use Payum\Core\Exception\RequestNotSupportedException;

final class GenerateClientTokenAction extends BaseApiAwareAction
{
    /** @param GenerateClientToken $request */
    public function execute($request): void
    {
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
