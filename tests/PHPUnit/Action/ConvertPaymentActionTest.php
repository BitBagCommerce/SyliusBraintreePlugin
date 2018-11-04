<?php

declare(strict_types=1);

namespace Tests\BitBag\SyliusBraintreePlugin\PHPUnit\Action;

use BitBag\SyliusBraintreePlugin\Action\ConvertPaymentAction;
use Payum\Core\Model\Payment;
use Payum\Core\Model\PaymentInterface;
use Payum\Core\Request\Convert;
use Payum\Core\Request\Generic;
use Payum\Core\Request\GetCurrency;
use Payum\Core\Security\TokenInterface;

class ConvertPaymentActionTest extends GenericActionTest
{
    protected $actionClass = ConvertPaymentAction::class;

    protected $requestClass = Convert::class;

    public function provideSupportedRequests()
    {
        return [
            [new $this->requestClass(new Payment(), 'array')],
            [new $this->requestClass($this->getMockBuilder(PaymentInterface::class)->getMock(), 'array')],
            [new $this->requestClass(new Payment(), 'array', $this->getMockBuilder(TokenInterface::class)->getMock())],
        ];
    }

    public function provideNotSupportedRequests()
    {
        return [
            ['foo'],
            [['foo']],
            [new \stdClass()],
            [$this->getMockForAbstractClass(Generic::class, [[]])],
            [new $this->requestClass(new \stdClass(), 'array')],
            [new $this->requestClass(new Payment(), 'foobar')],
            [new $this->requestClass($this->getMockBuilder(PaymentInterface::class)->getMock(), 'foobar')],
        ];
    }

    /**
     * @test
     */
    public function shouldCorrectlyConvertOrderToDetailsAndSetItBack()
    {
        $gatewayMock = $this->createMock('Payum\Core\GatewayInterface');
        $gatewayMock
            ->expects($this->once())
            ->method('execute')
            ->with($this->isInstanceOf('Payum\Core\Request\GetCurrency'))
            ->willReturnCallback(function (GetCurrency $request) {
                $request->name = 'Pound Sterling';
                $request->alpha3 = 'GBP';
                $request->numeric = 826;
                $request->exp = 2;
                $request->country = 'GB';
            })
        ;

        $order = new Payment();
        $order->setCurrencyCode('GBP');
        $order->setTotalAmount(123);

        $action = new ConvertPaymentAction();
        $action->setGateway($gatewayMock);

        $action->execute($convert = new Convert($order, 'array'));

        $details = $convert->getResult();

        $this->assertNotEmpty($details);

        $this->assertArrayNotHasKey('card', $details);

        $this->assertArrayHasKey('amount', $details);
        $this->assertEquals(1.23, $details['amount']);
    }

    /**
     * @test
     */
    public function shouldNotOverwriteAlreadySetExtraDetails()
    {
        $gatewayMock = $this->createMock('Payum\Core\GatewayInterface');
        $gatewayMock
            ->expects($this->once())
            ->method('execute')
            ->with($this->isInstanceOf('Payum\Core\Request\GetCurrency'))
            ->willReturnCallback(function (GetCurrency $request) {
                $request->name = 'Pound Sterling';
                $request->alpha3 = 'GBP';
                $request->numeric = 826;
                $request->exp = 2;
                $request->country = 'GB';
            })
        ;

        $order = new Payment();
        $order->setCurrencyCode('GBP');
        $order->setTotalAmount(123);
        $order->setDescription('order description');
        $order->setDetails([
            'foo' => 'fooVal',
        ]);

        $action = new ConvertPaymentAction();
        $action->setGateway($gatewayMock);

        $action->execute($convert = new Convert($order, 'array'));

        $details = $convert->getResult();

        $this->assertNotEmpty($details);

        $this->assertArrayHasKey('foo', $details);
        $this->assertEquals('fooVal', $details['foo']);
    }
}
