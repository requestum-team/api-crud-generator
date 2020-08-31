<?php

namespace Requestum\ApiGeneratorBundle\Model\Generator;

/**
 * Class AccessLevelEnum
 *
 * @package Requestum\ApiGeneratorBundle\Model\Generator
 */
final class AccessLevelEnum
{
    const ACCESS_LEVEL_PUBLIC = 'public';
    const ACCESS_LEVEL_PROTECTED = 'protected';
    const ACCESS_LEVEL_PRIVATE = 'private';

    /**
     * @return array
     */
    public static function getAccessLevels(): array
    {
        return [
            AccessLevelEnum::ACCESS_LEVEL_PUBLIC,
            AccessLevelEnum::ACCESS_LEVEL_PROTECTED,
            AccessLevelEnum::ACCESS_LEVEL_PRIVATE
        ];
    }
}
