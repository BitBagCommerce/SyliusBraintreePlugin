<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusBraintreePlugin\ApiClient;

use Braintree\PaymentMethodNonce;
use Braintree\Result\Error;
use Braintree\Result\Successful;
use Payum\Core\Bridge\Spl\ArrayObject;

interface BraintreeApiClientInterface
{
    public const FAILED = 'failed';

    public const AUTHORIZED = 'authorized';

    public const CAPTURED = 'captured';

    public const REFUNDED = 'refunded';

    public function initialise(array $options): void;

    public function generateClientToken(array $params): string;

    public function findPaymentMethodNonce(string $nonceString): PaymentMethodNonce;

    /**
     * @return Successful|Error
     */
    public function sale(ArrayObject $params);

    /**
     * @return Successful|Error
     */
    public function refund(string $transactionId);
}
