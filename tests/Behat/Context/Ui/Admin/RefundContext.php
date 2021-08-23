<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace Tests\BitBag\SyliusBraintreePlugin\Behat\Context\Ui\Admin;

use Behat\Behat\Context\Context;
use Sylius\Behat\Context\Ui\Admin\ManagingOrdersContext;
use Sylius\Component\Core\Model\OrderInterface;
use Tests\BitBag\SyliusBraintreePlugin\Behat\Mocker\BraintreeApiMocker;

final class RefundContext implements Context
{
    /** @var BraintreeApiMocker */
    private $braintreeApiMocker;

    /** @var ManagingOrdersContext */
    private $managingOrdersContext;

    public function __construct(
        BraintreeApiMocker $braintreeApiMocker,
        ManagingOrdersContext $managingOrdersContext
    ) {
        $this->braintreeApiMocker = $braintreeApiMocker;
        $this->managingOrdersContext = $managingOrdersContext;
    }

    /**
     * @When /^I mark (this order)'s braintree payment as refunded$/
     */
    public function iMarkThisOrdersBraintreePaymentAsRefunded(OrderInterface $order): void
    {
        $this->braintreeApiMocker->mockApiRefundedPayment(function () use ($order) {
            $this->managingOrdersContext->iMarkThisOrderSPaymentAsRefunded($order);
        });
    }
}
