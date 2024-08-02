<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusBraintreePlugin\Action;

use BitBag\SyliusBraintreePlugin\Request\Api\GenerateClientToken;
use BitBag\SyliusBraintreePlugin\Request\ObtainCardholderAuthentication;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Reply\HttpResponse;
use Payum\Core\Request\GetHttpRequest;
use Payum\Core\Request\RenderTemplate;

final class ObtainCardholderAuthenticationAction implements ActionInterface, GatewayAwareInterface
{
    use GatewayAwareTrait;

    private string $templateName;

    public function __construct(string $templateName)
    {
        $this->templateName = $templateName;
    }

    public function execute($request): void
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $details = ArrayObject::ensureArrayObject($request->getModel());

        $details->validateNotEmpty(['paymentMethodNonce', 'paymentMethodNonceInfo']);

        $paymentMethodNonceInfo = $details['paymentMethodNonceInfo'];

        if (array_key_exists('threeDSecureInfo', $paymentMethodNonceInfo) && array_key_exists('status', $paymentMethodNonceInfo['threeDSecureInfo'])) {
            return;
        }

        $this->gateway->execute($clientHttpRequest = new GetHttpRequest());

        if ('POST' == $clientHttpRequest->method && array_key_exists('threeDSecure_payment_method_nonce', $clientHttpRequest->request)) {
            $paymentMethodNonce = $clientHttpRequest->request['threeDSecure_payment_method_nonce'];

            $request->setResponse($paymentMethodNonce);

            return;
        }

        if (false == $details->offsetExists('clientToken')) {
            $this->generateClientToken($details);
        }

        $details->validateNotEmpty(['clientToken', 'paymentMethodNonce']);

        $this->gateway->execute($template = new RenderTemplate($this->templateName, [
            'formAction' => $clientHttpRequest->uri,
            'clientToken' => $details['clientToken'],
            'amount' => $details['amount'],
            'creditCard' => $details['paymentMethodNonce'],
            'details' => $details,
        ]));

        throw new HttpResponse($template->getResult());
    }

    public function supports($request): bool
    {
        return
            $request instanceof ObtainCardholderAuthentication &&
            $request->getModel() instanceof \ArrayAccess
        ;
    }

    protected function generateClientToken($details)
    {
        $request = new GenerateClientToken();

        $this->gateway->execute($request);

        $details['clientToken'] = $request->getResponse();
    }
}
