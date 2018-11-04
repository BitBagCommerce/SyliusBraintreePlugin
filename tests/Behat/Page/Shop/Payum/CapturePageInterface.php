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

use Sylius\Behat\Page\PageInterface;

interface CapturePageInterface extends PageInterface
{
    public function confirmPayment(string $paymentMethodNonce): void;
}
