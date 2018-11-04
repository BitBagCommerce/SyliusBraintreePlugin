<?php

declare(strict_types=1);

namespace BitBag\SyliusBraintreePlugin\Action\Api;

use BitBag\SyliusBraintreePlugin\Request\Api\FindPaymentMethodNonce;
use BitBag\SyliusBraintreePlugin\Request\Api\GenerateClientToken;
use Braintree\Exception\NotFound;
use Payum\Core\Exception\RequestNotSupportedException;

final class FindPaymentMethodNonceAction extends BaseApiAwareAction
{
    public function execute($request): void
    {
        /** @var $request GenerateClientToken */
        RequestNotSupportedException::assertSupports($this, $request);

        try {
            $paymentMethodNonce = $this->api->findPaymentMethodNonce($request->getNonceString());

            $request->setResponse($paymentMethodNonce);
        } catch (NotFound $exception) {
            return;
        }
    }

    public function supports($request): bool
    {
        return $request instanceof FindPaymentMethodNonce;
    }
}
