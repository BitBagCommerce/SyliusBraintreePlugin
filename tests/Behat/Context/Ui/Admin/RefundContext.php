<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
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
