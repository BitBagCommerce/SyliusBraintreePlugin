<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace Tests\BitBag\SyliusBraintreePlugin\Behat\Mocker;

use BitBag\SyliusBraintreePlugin\ApiClient\BraintreeApiClientInterface;
use Braintree\PaymentMethodNonce;
use Sylius\Behat\Service\Mocker\MockerInterface;
use Braintree\Transaction;

final class BraintreeApiMocker
{
    /** @var MockerInterface */
    private $mocker;

    public function __construct(MockerInterface $mocker)
    {
        $this->mocker = $mocker;
    }

    public function mockApiRefundedPayment(callable $action): void
    {
        $mock = $this->mocker->mockService('bitbag_sylius_braintree_plugin.api_client.braintree', BraintreeApiClientInterface::class);

        $mock
            ->shouldReceive('initialise')
        ;

        $mock
            ->shouldReceive('refund')
            ->andReturn((object) ['success' => true])
        ;

        $action();

        $this->mocker->unmockAll();
    }

    public function mockApiCreatePayment(callable $action): void
    {
        $mock = $this->mocker->mockService('bitbag_sylius_braintree_plugin.api_client.braintree', BraintreeApiClientInterface::class);

        $mock
            ->shouldReceive('initialise')
        ;

        $mock
            ->shouldReceive('generateClientToken')
            ->andReturn('test')
        ;

        $action();

        $this->mocker->unmockAll();
    }

    public function mockApiSuccessfulPayment(callable $action): void
    {
        $mock = $this->mocker->mockService('bitbag_sylius_braintree_plugin.api_client.braintree', BraintreeApiClientInterface::class);

        $mock
            ->shouldReceive('initialise')
        ;

        $mock
            ->shouldReceive('generateClientToken')
            ->andReturn('test')
        ;

        $paymentMethodNonce = \Mockery::mock('paymentMethodNonce', PaymentMethodNonce::class);

        $result = \Mockery::mock('result');

        $transaction = \Mockery::mock('transaction', Transaction::class);

        $transaction->status = Transaction::SUBMITTED_FOR_SETTLEMENT;

        $paymentMethodNonce->type = 'type';

        $result->success = true;
        $result->transaction = $transaction;
        $result->errors = [];

        $mock
            ->shouldReceive('findPaymentMethodNonce')
            ->andReturn($paymentMethodNonce)
        ;

        $mock
            ->shouldReceive('sale')
            ->andReturn($result)
        ;

        $action();

        $this->mocker->unmockAll();
    }

    public function mockApiFailedPayment(callable $action): void
    {
        $mock = $this->mocker->mockService('bitbag_sylius_braintree_plugin.api_client.braintree', BraintreeApiClientInterface::class);

        $mock
            ->shouldReceive('initialise')
        ;

        $mock
            ->shouldReceive('generateClientToken')
            ->andReturn('test')
        ;

        $paymentMethodNonce = \Mockery::mock('paymentMethodNonce', PaymentMethodNonce::class);

        $result = \Mockery::mock('result');

        $transaction = \Mockery::mock('transaction', Transaction::class);

        $transaction->status = Transaction::SUBMITTED_FOR_SETTLEMENT;

        $paymentMethodNonce->type = 'type';

        $result->success = false;
        $result->transaction = $transaction;
        $result->errors = [];

        $mock
            ->shouldReceive('findPaymentMethodNonce')
            ->andReturn($paymentMethodNonce)
        ;

        $mock
            ->shouldReceive('sale')
            ->andReturn($result)
        ;

        $action();

        $this->mocker->unmockAll();
    }
}
