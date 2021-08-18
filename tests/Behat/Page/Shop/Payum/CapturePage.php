<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
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
