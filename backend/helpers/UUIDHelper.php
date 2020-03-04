<?php

declare(strict_types=1);

namespace yii\helpers;

class UUIDHelper
{
    const UUID_REGEXP = '/^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i';

    public static function string2bin(string $uuid): string
    {
        return pack("H*", str_replace('-', '', $uuid));
    }

    public static function bin2string(string $binary): string
    {
        return strtolower(join("-", unpack("H8time_low/H4time_mid/H4time_hi/H4clock_seq_hi/H12clock_seq_low", $binary)));
    }

    public static function isUUID(string $uuid): bool
    {
        return (bool)preg_match(self::UUID_REGEXP,$uuid);
    }
}