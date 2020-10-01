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
    public static function hasAttributes(string $url): bool
    {
        return preg_match(self::ATTRIBUT_PATTERN, $url);
    }

    /**
     * @param string $ref
     *
     * @return string|null
     */
    public static function getReferencedSchemaObjectName(string $ref): ?string
    {
        $array = explode('/', $ref);

        return count($array) > 0 ? array_pop($array): null;
    }

    /**
     * @param string $objectName
     *
     * @return string | null
     */
    public static function getEntityNameFromObjectName(string $objectName): ?string
    {
        if (CommonHelper::isEntity($objectName)) {
            return substr($objectName, 0, -6);
        }

        return null;
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public static function makeSetterName(string $name): string
    {
        return 'set' . ucfirst($name);
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public static function makeGetterName(string $name): string
    {
        return 'get' . ucfirst($name);
    }

    /**
     * @param array $params
     *
     * @return string
     */
    public static function transformToEntityColumnParameters(array $params): string
    {
        $result = [];
        foreach ($params as $key => $value) {
            if (is_bool($value)) {
                $value = $value === true ? 'true': 'false';
                $param = sprintf('%s=%s',$key, $value);
            } else if (is_int($value)) {
                $param = sprintf('%s=%d', $key, $value);
            } else if (is_float($value) || is_double($value)) {
                $param = sprintf('%s=%f', $key, $value);
            } else {
                if ($key === 'name') {
                    $value = sprintf('`%s`', $value);
                }

                $param = sprintf('%s="%s"',$key, $value);
            }

            $result[] = $param;
        }

        return implode(', ', $result);
    }

    /**
     * @param array $pieces
     * @param string $glue
     *
     * @return string
     */
    public static function transformToDelimitedString(array $pieces, string $glue = ', ')
    {
        return implode($glue, array_map(function($val) {return sprintf("\"%s\"", $val);}, $pieces));
    }
}
