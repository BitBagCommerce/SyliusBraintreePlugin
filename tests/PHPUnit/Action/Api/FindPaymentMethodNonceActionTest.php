<?php

declare(strict_types=1);

namespace Tests\BitBag\SyliusBraintreePlugin\PHPUnit\Action\Api;

use BitBag\SyliusBraintreePlugin\Action\Api\FindPaymentMethodNonceAction;
use BitBag\SyliusBraintreePlugin\ApiClient\BraintreeApiClientInterface;
use BitBag\SyliusBraintreePlugin\Request\Api\FindPaymentMethodNonce;
use Braintree\PaymentMethodNonce;
use PHPUnit\Framework\TestCase;

class FindPaymentMethodNonceActionTest extends TestCase
{
    /**
     * @test
     */
    public function shouldResponseWithBraintreePaymentMethodNonceObject()
    {
        $apiMock = $this->createApiMock();

        $apiMock
            ->expects($this->once())
            ->method('findPaymentMethodNonce')
            ->will($this->returnValue(PaymentMethodNonce::factory([
                'nonce' => 'first_nonce',
                'type' => 'aPaymentMethodType',
            ])))
        ;

        $action = new FindPaymentMethodNonceAction();
        $action->setApi($apiMock);

        $action->execute($request = new FindPaymentMethodNonce('first_nonce'));

        $paymentMethodNonceInfo = $request->getResponse();

        $this->assertEquals('first_nonce', $paymentMethodNonceInfo->nonce);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|BraintreeApiClientInterface
     */
    protected function createApiMock()
    {
        return $this->createMock(BraintreeApiClientInterface::class);
    }
}
