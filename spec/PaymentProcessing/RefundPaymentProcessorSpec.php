<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace spec\BitBag\SyliusBraintreePlugin\PaymentProcessing;

use BitBag\SyliusBraintreePlugin\ApiClient\BraintreeApiClientInterface;
use BitBag\SyliusBraintreePlugin\BraintreeGatewayFactory;
use BitBag\SyliusBraintreePlugin\PaymentProcessing\PaymentProcessorInterface;
use BitBag\SyliusBraintreePlugin\PaymentProcessing\RefundPaymentProcessor;
use Payum\Core\Model\GatewayConfigInterface;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Symfony\Component\HttpFoundation\Session\Session;

final class RefundPaymentProcessorSpec extends ObjectBehavior
{
    function let(Session $session, BraintreeApiClientInterface $braintreeApiClient): void
    {
        $this->beConstructedWith($session, $braintreeApiClient);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(RefundPaymentProcessor::class);
    }

    function it_implements_payment_processor_interface(): void
    {
        $this->shouldHaveType(PaymentProcessorInterface::class);
    }

    function it_processes(
        PaymentInterface $payment,
        PaymentMethodInterface $paymentMethod,
        GatewayConfigInterface $gatewayConfig,
        BraintreeApiClientInterface $braintreeApiClient
    ): void {
        $gatewayConfig->getFactoryName()->willReturn(BraintreeGatewayFactory::FACTORY_NAME);
        $gatewayConfig->getConfig()->willReturn([]);
        $paymentMethod->getGatewayConfig()->willReturn($gatewayConfig);
        $paymentMethod->getGatewayConfig()->willReturn($gatewayConfig);
        $payment->getMethod()->willReturn($paymentMethod);
        $payment->getDetails()->willReturn([
            'sale' => [
                'transaction' => [
                    'id' => 'test',
                ]
            ]
        ]);
        $braintreeApiClient->refund('test')->willReturn((object) ['success' => true]);

        $braintreeApiClient->initialise([])->shouldBeCalled();

        $this->process($payment);
    }
}
