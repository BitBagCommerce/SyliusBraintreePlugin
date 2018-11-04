<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace Tests\BitBag\SyliusBraintreePlugin\Behat\Context\Ui\Shop;

use Behat\Behat\Context\Context;
use Sylius\Behat\Page\Shop\Order\ShowPageInterface;
use Tests\BitBag\SyliusBraintreePlugin\Behat\Mocker\BraintreeApiMocker;
use Sylius\Behat\Page\Shop\Checkout\CompletePageInterface;
use Tests\BitBag\SyliusBraintreePlugin\Behat\Page\Shop\Payum\CapturePageInterface;

final class CheckoutContext implements Context
{
    /** @var CompletePageInterface */
    private $summaryPage;

    /** @var ShowPageInterface */
    private $orderDetails;

    /** @var BraintreeApiMocker */
    private $braintreeApiMocker;

    /** @var CapturePageInterface */
    private $capturePage;

    public function __construct(
        CompletePageInterface $summaryPage,
        ShowPageInterface $orderDetails,
        BraintreeApiMocker $braintreeApiMocker,
        CapturePageInterface $capturePage
    ) {
        $this->summaryPage = $summaryPage;
        $this->orderDetails = $orderDetails;
        $this->braintreeApiMocker = $braintreeApiMocker;
        $this->capturePage = $capturePage;
    }

    /**
     * @When I confirm my order with Braintree payment
     * @Given I have confirmed my order with Braintree payment
     */
    public function iConfirmMyOrderWithBraintreePayment(): void
    {
        $this->braintreeApiMocker->mockApiCreatePayment(function () {
            $this->summaryPage->confirmOrder();
        });
    }

    /**
     * @When I sign in to Braintree and pay successfully
     */
    public function iSignInToBraintreeAndPaySuccessfully(): void
    {
        $this->braintreeApiMocker->mockApiSuccessfulPayment(function () {
            $this->capturePage->confirmPayment('paypal');
        });
    }

    /**
     * @When I try to pay again Braintree payment
     */
    public function iTryToPayAgainBraintreePayment(): void
    {
        $this->braintreeApiMocker->mockApiCreatePayment(function () {
            $this->orderDetails->pay();
        });
    }

    /**
     * @Then I should be notified that my payment has been failed
     */
    public function iShouldBeNotifiedThatMyPaymentHasBeenFailed(): void
    {
        $this->assertNotification('Payment has failed.');
    }

    /**
     * @Given I have failed Braintree payment
     * @When I fail my Braintree payment
     */
    public function iHaveFailedBraintreePayment(): void
    {
        $this->braintreeApiMocker->mockApiFailedPayment(function () {
            $this->capturePage->confirmPayment('paypal');
        });
    }

    private function assertNotification($expectedNotification): void
    {
        $notifications = $this->orderDetails->getNotifications();
        $hasNotifications = '';

        foreach ($notifications as $notification) {
            $hasNotifications .= $notification;
            if ($notification === $expectedNotification) {
                return;
            }
        }

        throw new \RuntimeException(sprintf('There is no notificaiton with "%s". Got "%s"', $expectedNotification, $hasNotifications));
    }
}
