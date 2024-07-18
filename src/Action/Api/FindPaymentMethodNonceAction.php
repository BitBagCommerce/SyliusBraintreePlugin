<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusBraintreePlugin\Action\Api;

use BitBag\SyliusBraintreePlugin\Request\Api\FindPaymentMethodNonce;
use BitBag\SyliusBraintreePlugin\Request\Api\GenerateClientToken;
use Braintree\Exception\NotFound;
use Payum\Core\Exception\RequestNotSupportedException;

final class FindPaymentMethodNonceAction extends BaseApiAwareAction
{
    /** @param GenerateClientToken $request */
    public function execute($request): void
    {
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
