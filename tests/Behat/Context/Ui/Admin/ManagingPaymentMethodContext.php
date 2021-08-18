<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace Tests\BitBag\SyliusBraintreePlugin\Behat\Context\Ui\Admin;

use Behat\Behat\Context\Context;
use Tests\BitBag\SyliusBraintreePlugin\Behat\Page\Admin\PaymentMethod\CreatePageInterface;
use Webmozart\Assert\Assert;

final class ManagingPaymentMethodContext implements Context
{
    /** @var CreatePageInterface */
    private $createPage;

    public function __construct(CreatePageInterface $createPage)
    {
        $this->createPage = $createPage;
    }

    /**
     * @Given I want to create a new Braintree payment method
     */
    public function iWantToCreateANewBraintreePaymentMethod(): void
    {
        $this->createPage->open(['factory' => 'braintree']);
    }

    /**
     * @Then I should be notified that :fields fields cannot be blank
     */
    public function iShouldBeNotifiedThatCannotBeBlank(string $fields): void
    {
        $fields = explode(',', $fields);

        foreach ($fields as $field) {
            Assert::true($this->createPage->containsErrorWithMessage(sprintf(
                '%s cannot be blank.',
                trim($field)
            )));
        }
    }

    /**
     * @Then I should be notified that :message
     */
    public function iShouldBeNotifiedThat(string $message): void
    {
        Assert::true($this->createPage->containsErrorWithMessage($message));
    }

    /**
     * @When I fill the Merchant ID with :merchantId
     */
    public function iFillTheMerchantIdWith(string $merchantId): void
    {
        $this->createPage->setMerchantId($merchantId);
    }

    /**
     * @When I fill the Public Key with :publicKey
     */
    public function iFillThePublicKeyWith(string $publicKey): void
    {
        $this->createPage->setPublicKey($publicKey);
    }

    /**
     * @When I fill the Private Key with :privateKey
     */
    public function iFillThePrivateKeyWith(string $privateKey): void
    {
        $this->createPage->setPrivateKey($privateKey);
    }
}
