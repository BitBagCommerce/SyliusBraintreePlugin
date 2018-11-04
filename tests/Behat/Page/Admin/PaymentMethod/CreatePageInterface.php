<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace Tests\BitBag\SyliusBraintreePlugin\Behat\Page\Admin\PaymentMethod;

use Sylius\Behat\Page\Admin\Crud\CreatePageInterface as BaseCreatePageInterface;

interface CreatePageInterface extends BaseCreatePageInterface
{
    public function setMerchantId(string $merchantId): void;

    public function setPublicKey(string $publicKey): void;

    public function setPrivateKey(string $privateKey): void;

    public function checkSandbox(): void;

    public function containsErrorWithMessage(string $message, bool $strict = true): bool;
}
