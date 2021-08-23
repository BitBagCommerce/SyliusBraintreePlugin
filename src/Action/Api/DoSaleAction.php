<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusBraintreePlugin\Action\Api;

use BitBag\SyliusBraintreePlugin\Request\Api\DoSale;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;

final class DoSaleAction extends BaseApiAwareAction
{
    public function execute($request): void
    {
        /** @var $request DoSale */
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
