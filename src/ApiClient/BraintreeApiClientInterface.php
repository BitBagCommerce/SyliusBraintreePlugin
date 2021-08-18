<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
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
     * @param ArrayObject $params
     *
     * @return Successful|Error
     */
    public function sale(ArrayObject $params);

    /**
     * @param string $transactionId
     *
     * @return Successful|Error
     */
    public function refund(string $transactionId);
}
