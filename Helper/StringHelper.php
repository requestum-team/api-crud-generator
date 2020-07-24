<?php

namespace Requestum\ApiGeneratorBundle\Helper;

/**
 * Class StringHelper
 *
 * @package Requestum\ApiGeneratorBundle\Helper
 */
class StringHelper
{
    const ATTRIBUT_PATTERN = '/\{\w+\}/';

    /**
     * @param string $value
     *
     * @return string
     */
    public static function camelCaseToSnakeCaseName(string $value): string
    {
        return strtolower(preg_replace('/[A-Z]/', '_\\0', lcfirst($value)));
    }

    /**
     * @param string $value
     *
     * @return string
     */
    public static function snakeCaseToCamelCaseName(string $value): string
    {
        $value = self::camelCaseToSnakeCaseName($value);

        $value = str_replace('_', '', ucwords($value, '_'));

        return lcfirst($value);
    }

    /**
     * @param string $string
     *
     * @return string
     */
    public static function pluralToSingular(string $string): string
    {
        if (strtolower(substr($string, -3) === 'ies')) {
            return strtolower(substr($string, 0,-3) . 'y');
        }

        if (strtolower(substr($string, -1) === 's')) {
            return strtolower(substr($string, 0, -1));
        }

        return $string;
    }

    /**
     * @param string $url
     *
     * @return bool
     */
    public static function hasAttributs(string $url): bool
    {
        return preg_match(self::ATTRIBUT_PATTERN, $url);
    }
}
