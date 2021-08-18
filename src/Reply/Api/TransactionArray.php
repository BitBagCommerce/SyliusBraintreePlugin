<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
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
