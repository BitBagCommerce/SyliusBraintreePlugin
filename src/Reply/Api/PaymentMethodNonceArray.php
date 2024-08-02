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
use Braintree\PaymentMethodNonce;

final class PaymentMethodNonceArray
{
    public static function toArray(PaymentMethodNonce $object): array
    {
        if (null === $object) {
            return [];
        }

        $array = ArrayUtils::extractPropertiesToArray($object, [
            'nonce', 'consumed', 'default', 'type', 'threeDSecureInfo', 'details',
        ]);

        if (array_key_exists('threeDSecureInfo', $array)) {
            $array['threeDSecureInfo'] = ArrayUtils::extractPropertiesToArray($array['threeDSecureInfo'], [
                'enrolled', 'liabilityShiftPossible', 'liabilityShifted', 'status',
            ]);
        }

        if (array_key_exists('details', $array)) {
            $array['details'] = ArrayUtils::extractPropertiesToArray($array['details'], [
                'cardType', 'lastTwo', 'correlationId', 'email', 'payerInfo', 'username',
            ]);
        }

        return $array;
    }
}
