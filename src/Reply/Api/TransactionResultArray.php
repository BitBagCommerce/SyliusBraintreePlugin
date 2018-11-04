<?php

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
