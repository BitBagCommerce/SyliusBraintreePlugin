<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusBraintreePlugin\PaymentProcessing;

use BitBag\SyliusBraintreePlugin\ApiClient\BraintreeApiClientInterface;
use BitBag\SyliusBraintreePlugin\BraintreeGatewayFactory;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Sylius\Component\Resource\Exception\UpdateHandlingException;
use Symfony\Component\HttpFoundation\Session\Session;

final class RefundPaymentProcessor implements PaymentProcessorInterface
{
    /** @var Session */
    private $session;

    /** @var BraintreeApiClientInterface */
    private $braintreeApiClient;

    public function __construct(Session $session, BraintreeApiClientInterface $braintreeApiClient)
    {
        $this->session = $session;
        $this->braintreeApiClient = $braintreeApiClient;
    }

    public function process(PaymentInterface $payment): void
    {
        /** @var PaymentMethodInterface $paymentMethod */
        $paymentMethod = $payment->getMethod();

        if (BraintreeGatewayFactory::FACTORY_NAME !== $paymentMethod->getGatewayConfig()->getFactoryName()) {
            return;
        }

        $details = $payment->getDetails();

        if (false === isset($details['sale']['transaction']['id'])) {
            $this->session->getFlashBag()->add('info', 'The payment refund was made only locally.');

            return;
        }

        $gatewayConfig = $paymentMethod->getGatewayConfig()->getConfig();

        $this->braintreeApiClient->initialise($gatewayConfig);

        try {
            $result = $this->braintreeApiClient->refund($details['sale']['transaction']['id']);

            if (true === $result->success) {
                return;
            }

            throw new \RuntimeException($result->message);
        } catch (\Exception $exception) {
            $message = $exception->getMessage();

            $this->session->getFlashBag()->add('error', $message);

            throw new UpdateHandlingException();
        }
    }
}
