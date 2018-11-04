<?php

declare(strict_types=1);

namespace Tests\BitBag\SyliusBraintreePlugin\PHPUnit\Action\Api;

use BitBag\SyliusBraintreePlugin\Action\Api\GenerateClientTokenAction;
use BitBag\SyliusBraintreePlugin\ApiClient\BraintreeApiClientInterface;
use BitBag\SyliusBraintreePlugin\Request\Api\GenerateClientToken;
use PHPUnit\Framework\TestCase;

class GenerateClientTokenActionTest extends TestCase
{
    /**
     * @test
     */
    public function shouldRespondWithClientTokenString()
    {
        $apiMock = $this->createApiMock();

        $apiMock
            ->expects($this->once())
            ->method('generateClientToken')
            ->will($this->returnValue('aClientToken'))
        ;

        $action = new GenerateClientTokenAction();
        $action->setApi($apiMock);

        $action->execute($request = new GenerateClientToken());

        $this->assertEquals('aClientToken', $request->getResponse());
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|BraintreeApiClientInterface
     */
    protected function createApiMock()
    {
        return $this->createMock(BraintreeApiClientInterface::class);
    }
}
