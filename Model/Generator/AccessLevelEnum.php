<?php

namespace Requestum\ApiGeneratorBundle\Model\Generator;

/**
 * Class AccessLevelEnum
 *
 * @package Requestum\ApiGeneratorBundle\Model\Generator
 */
final class AccessLevelEnum
{
    const ACCESS_LELEV_PUBLIC = 'public';

    const ACCESS_LELEV_PROTECTED = 'protected';

    const ACCESS_LELEV_PRIVATE = 'private';

    /**
     * @return array
     */
    public static function getAccessLevels(): array
    {
        return [
            AccessLevelEnum::ACCESS_LELEV_PUBLIC,
            AccessLevelEnum::ACCESS_LELEV_PROTECTED,
            AccessLevelEnum::ACCESS_LELEV_PRIVATE
        ];
    }
}
