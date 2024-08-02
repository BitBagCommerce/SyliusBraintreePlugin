<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusBraintreePlugin\Reply\Api;

use BitBag\SyliusBraintreePlugin\Util\ArrayUtils;
use Braintree\Transaction;

final class TransactionArray
{
    public static function toArray(Transaction $object): array
    {
        if (null === $object) {
            return [];
        }

        $array = ArrayUtils::extractPropertiesToArray($object, [
            'id', 'status', 'type', 'currentIsoCode', 'amount',
            'merchantAccountId', 'subMerchantAccountId', 'masterMerchantAccountId',
            'orderId', 'createdAt', 'updatedAt', 'customer', 'billing', 'refundId',
            'refundIds', 'refundedTransactionId', 'partialSettlementTransactionIds',
            'authorizedTransactionId', 'settlementBatchId', 'shipping', 'customFields',
            'avsErrorResponseCode', 'avsPostalCodeResponseCode', 'avsStreetAddressResponseCode',
            'cvvResponseCode', 'gatewayRejectionReason', 'processorAuthorizationCode',
            'processorResponseCode', 'processorResponseText', 'additionalProcessorResponse',
            'voiceReferralNumber', 'purchaseOrderNumber', 'taxAmount', 'taxExempt', 'creditCard',
            'planId', 'subscriptionId', 'subscription', 'addOns', 'discounts', 'recurring',
            'channel', 'serviceFeeAmount', 'escrowStatus', /*disbursementDetails,*/
            'paymentInstrumentType', 'processorSettlementResponseCode',
            'processorSettlementResponseText', 'threeDSecureInfo', /*, creditCardDetails */
        ]);

        return $array;
    }
}
