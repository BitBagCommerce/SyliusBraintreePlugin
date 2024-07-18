<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusBraintreePlugin\Action\Api;

use BitBag\SyliusBraintreePlugin\Request\Api\DoSale;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;

final class DoSaleAction extends BaseApiAwareAction
{
    /** @param DoSale $request */
    public function execute($request): void
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $requestParams = $this->getSaleRequestParams($request);

        $transactionResult = $this->api->sale($requestParams);

        $request->setResponse($transactionResult);
    }

    private function getSaleRequestParams(DoSale $request): ArrayObject
    {
        $details = ArrayObject::ensureArrayObject($request->getModel());

        $details->validateNotEmpty(['amount']);

        $requestParams = new ArrayObject();

        $forwardParams = [
            'amount',
            'paymentMethodNonce',
            'paymentMethodToken',
            'creditCard',
            'billing',
            'shipping',
            'customer',
            'orderId',
        ];

        foreach ($forwardParams as $paramName) {
            if ($details->offsetExists($paramName)) {
                $requestParams[$paramName] = $details[$paramName];
            }
        }

        if ($details->offsetExists('saleOptions')) {
            $requestParams['options'] = $details['saleOptions'];
        }

        return $requestParams;
    }

    public function supports($request): bool
    {
        return
            $request instanceof DoSale &&
            $request->getModel() instanceof \ArrayAccess
        ;
    }
}
