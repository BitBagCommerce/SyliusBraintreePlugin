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

final class TransactionResultArray
{
    public static function toArray($object): array
    {
        if (null === $object) {
            return [];
        }

        $array = ArrayUtils::extractPropertiesToArray($object, [
            'success', 'transaction', 'errors',
        ]);

        if (array_key_exists('transaction', $array) && null !== $array['transaction']) {
            $array['transaction'] = TransactionArray::toArray($array['transaction']);
        }

        return $array;
    }
}
