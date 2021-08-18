<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusBraintreePlugin\Action;

use BitBag\SyliusBraintreePlugin\ApiClient\BraintreeApiClientInterface;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\GetStatusInterface;

final class StatusAction implements ActionInterface
{
    public function execute($request): void
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $details = ArrayObject::ensureArrayObject($request->getModel());

        $status = $details['status'];

        if (null != $status) {
            switch ($status) {
                case BraintreeApiClientInterface::FAILED:
                    $request->markFailed();

                    return;
                case BraintreeApiClientInterface::AUTHORIZED:
                    if ($this->hasSuccessfulTransaction($details)) {
                        $request->markAuthorized();
                    } else {
                        $request->markUnknown();
                    }

                    return;
                case BraintreeApiClientInterface::CAPTURED:
                    if ($this->hasSuccessfulTransaction($details)) {
                        $request->markCaptured();
                    } else {
                        $request->markUnknown();
                    }

                    return;
                case BraintreeApiClientInterface::REFUNDED:
                    if ($this->hasSuccessfulTransaction($details)) {
                        $request->markRefunded();
                    } else {
                        $request->markUnknown();
                    }

                    return;
            }
        }

        if ($details['paymentMethodNonce']) {
            $request->markPending();

            return;
        }

        $request->markNew();
    }

    public function supports($request): bool
    {
        return
            $request instanceof GetStatusInterface &&
            $request->getModel() instanceof \ArrayAccess
        ;
    }

    private function hasSuccessfulTransaction($details): bool
    {
        return $details['sale'] && $details['sale']['success'];
    }
}
