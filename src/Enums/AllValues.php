<?php

namespace Celoain\ApiWrapper\Enums;

/**
 * @method cases()
 */
trait AllValues
{
    /**
     * @return array<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
