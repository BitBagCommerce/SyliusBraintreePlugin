<?php

declare(strict_types=1);

namespace Tests\BitBag\SyliusBraintreePlugin\PHPUnit\Action\Api;

use BitBag\SyliusBraintreePlugin\Action\Api\DoSaleAction;
use BitBag\SyliusBraintreePlugin\ApiClient\BraintreeApiClientInterface;
use BitBag\SyliusBraintreePlugin\Request\Api\DoSale;
use Braintree\Result;
use Braintree\Transaction;
use PHPUnit\Framework\TestCase;

class DoSaleActionTest extends TestCase
{
    /**
     * @test
     */
    public function shouldRespondWithBraintreeTransaction()
    {
        $apiMock = $this->createApiMock();

        $apiMock
            ->expects($this->once())
            ->method('sale')
            ->will($this->returnValue(new Result\Successful(Transaction::factory([
                'id' => 'aTransactionId',
            ]))))
        ;

        $action = new DoSaleAction();
        $action->setApi($apiMock);

        $action->execute($request = new DoSale([
            'amount' => 10,
            'paymentMethodNonce' => 'first_nonce',
        ]));
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|BraintreeApiClientInterface
     */
    protected function createApiMock()
    {
        return $this->createMock(BraintreeApiClientInterface::class);
    }
}
