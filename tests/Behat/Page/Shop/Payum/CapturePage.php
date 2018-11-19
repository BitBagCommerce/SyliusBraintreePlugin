<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace Tests\BitBag\SyliusBraintreePlugin\Behat\Page\Shop\Payum;

use FriendsOfBehat\PageObjectExtension\Page\Page;

final class CapturePage extends Page implements CapturePageInterface
{
    public function confirmPayment(string $paymentMethodNonce): void
    {
        $this->getDocument()->findById('payment_method_nonce')->setValue($paymentMethodNonce);

        $this->getDocument()->pressButton('submit_btn');
    }

    protected function getUrl(array $urlParameters = []): string
    {
        return '';
    }
}
