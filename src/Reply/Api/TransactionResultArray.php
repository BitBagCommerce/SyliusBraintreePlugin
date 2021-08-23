<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
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
