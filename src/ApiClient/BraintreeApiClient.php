<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusBraintreePlugin\ApiClient;

use Braintree\ClientToken;
use Braintree\Configuration;
use Braintree\PaymentMethodNonce;
use Braintree\Result\Error;
use Braintree\Result\Successful;
use Braintree\Transaction;
use Payum\Core\Bridge\Spl\ArrayObject;

class BraintreeApiClient implements BraintreeApiClientInterface
{
    /** @var array */
    protected $options = [];

    public function initialise(array $options): void
    {
        $this->options = $options;

        Configuration::reset();

        $environment = 'sandbox';

        if (array_key_exists('environment', $this->options) && null !== $this->options['environment']) {
            $environment = $this->options['environment'];
        } elseif (array_key_exists('sandbox', $this->options) && null !== $this->options['sandbox']) {
            $environment = !$this->options['sandbox'] ? 'production' : 'sandbox';
        }

        Configuration::environment($environment);

        Configuration::merchantId($this->options['merchantId']);
        Configuration::publicKey($this->options['publicKey']);
        Configuration::privateKey($this->options['privateKey']);
    }

    public function generateClientToken(array $params): string
    {
        if (array_key_exists('merchantAccountId', $this->options) && null !== $this->options['merchantAccountId']) {
            $params['merchantAccountId'] = $this->options['merchantAccountId'];
        }

        return ClientToken::generate($params);
    }

    public function findPaymentMethodNonce(string $nonceString): PaymentMethodNonce
    {
        return PaymentMethodNonce::find($nonceString);
    }

    /**
     * @return Error|Successful
     */
    public function sale(ArrayObject $params)
    {
        $options = $params->offsetExists('options') ? $params['options'] : [];

        if (null !== $this->options['storeInVault'] && !isset($options['storeInVault'])) {
            $options['storeInVault'] = $this->options['storeInVault'];
        }

        if (null !== $this->options['storeInVaultOnSuccess'] && !isset($options['storeInVaultOnSuccess'])) {
            $options['storeInVaultOnSuccess'] = $this->options['storeInVaultOnSuccess'];
        }

        if (null !== $this->options['addBillingAddressToPaymentMethod'] &&
            !isset($options['addBillingAddressToPaymentMethod']) &&
            $params->offsetExists('billing')) {
            $options['addBillingAddressToPaymentMethod'] = $this->options['addBillingAddressToPaymentMethod'];
        }

        if (null !== $this->options['storeShippingAddressInVault'] &&
            !isset($options['storeShippingAddressInVault']) &&
            $params->offsetExists('shipping')) {
            $options['storeShippingAddressInVault'] = $this->options['storeShippingAddressInVault'];
        }

        $params['options'] = $options;

        if (array_key_exists('merchantAccountId', $this->options) && null !== $this->options['merchantAccountId']) {
            $params['merchantAccountId'] = $this->options['merchantAccountId'];
        }

        return Transaction::sale((array) $params);
    }

    /**
     * @return Successful|Error
     */
    public function refund(string $transactionId)
    {
        return Transaction::refund($transactionId);
    }
}
