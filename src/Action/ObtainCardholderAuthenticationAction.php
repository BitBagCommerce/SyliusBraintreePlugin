<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
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

    /** @var string */
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
