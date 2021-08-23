<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusBraintreePlugin\Action;

use BitBag\SyliusBraintreePlugin\Request\Api\GenerateClientToken;
use BitBag\SyliusBraintreePlugin\Request\ObtainPaymentMethodNonce;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Reply\HttpResponse;
use Payum\Core\Request\GetHttpRequest;
use Payum\Core\Request\RenderTemplate;

final class ObtainPaymentMethodNonceAction implements ActionInterface, GatewayAwareInterface
{
    use GatewayAwareTrait;

    /** @var string */
    private $templateName;

    protected bool $cardholderAuthenticationRequired;

    public function __construct(string $templateName)
    {
        $this->templateName = $templateName;

        $this->cardholderAuthenticationRequired = true;
    }

    public function setCardholderAuthenticationRequired($value): void
    {
        $this->cardholderAuthenticationRequired = $value;
    }

    public function execute($request): void
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $details = ArrayObject::ensureArrayObject($request->getModel());

        if (true == $details->offsetExists('paymentMethodNonce')) {
            $request->setResponse($details['paymentMethodNonce']);

            return;
        }

        $this->gateway->execute($clientHttpRequest = new GetHttpRequest());

        if (array_key_exists('payment_method_nonce', $clientHttpRequest->request)) {
            $paymentMethodNonce = $clientHttpRequest->request['payment_method_nonce'];

            $request->setResponse($paymentMethodNonce);

            return;
        }

        if (false == $details->offsetExists('clientToken')) {
            $this->generateClientToken($details);
        }

        $details->validateNotEmpty(['clientToken']);

        $this->gateway->execute($template = new RenderTemplate($this->templateName, [
            'formAction' => $clientHttpRequest->uri,
            'clientToken' => $details['clientToken'],
            'amount' => $details['amount'],
            'details' => $details,
            'obtainCardholderAuthentication' => $this->cardholderAuthenticationRequired,
        ]));

        throw new HttpResponse($template->getResult());
    }

    public function supports($request): bool
    {
        return
            $request instanceof ObtainPaymentMethodNonce &&
            $request->getModel() instanceof \ArrayAccess
        ;
    }

    protected function generateClientToken(ArrayObject $details): void
    {
        $request = new GenerateClientToken();

        $this->gateway->execute($request);

        $details['clientToken'] = $request->getResponse();
    }
}
