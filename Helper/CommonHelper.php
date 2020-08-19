<?php

namespace Requestum\ApiGeneratorBundle\Helper;

/**
 * Class CommonHelper
 *
 * @package Requestum\ApiGeneratorBundle\Helper
 */
class CommonHelper
{
    /**
     * @param string $objectName
     *
     * @return bool
     */
    public static function isEntity(string $objectName): bool
    {
        return false !== strpos(strtolower($objectName), 'entity');
    }

    /**
     * @param string $objectName
     *
     * @return bool
     */
    public static function isForm(string $objectName): bool
    {
        return
            (false !== strpos(strtolower($objectName), 'input') ||
                false !== strpos(strtolower($objectName), 'create') ||
                false !== strpos(strtolower($objectName), 'update') ||
                false !== strpos(strtolower($objectName), 'patch'));
    }

    /**
     * @param array $openApiSchema
     * @param string $ref
     *
     * @return array|null
     */
    public static function getComponentsSchemaDataByPath(array $openApiSchema, string $ref): ?array
    {
        [,,$path, $node] = explode('/', $ref);

        return $openApiSchema['components'][$path][$node] ?? null;
    }
}
