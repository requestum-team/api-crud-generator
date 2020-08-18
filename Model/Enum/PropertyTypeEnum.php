<?php

namespace Requestum\ApiGeneratorBundle\Model\Enum;

/**
 * Class PropertyTypeEnum
 *
 * @package Requestum\ApiGeneratorBundle\Model
 */
final class PropertyTypeEnum
{
    const TYPE_STRING = 'string';
    const TYPE_NUMBER = 'number';
    const TYPE_INTEGER = 'integer';
    const TYPE_BOOLEAN = 'boolean';
    const TYPE_ARRAY = 'array';
    const TYPE_OBJECT = 'object';

    const TYPE_PRIMARY_AUTO = 'primary_auto';
    const TYPE_FLOAT = 'float';
    const TYPE_DOUBLE = 'double';
    const TYPE_DECIMAL = 'decimal';
}
