<?php

namespace Requestum\ApiGeneratorBundle\Service;

use Requestum\ApiGeneratorBundle\Helper\CommonHelper;
use Requestum\ApiGeneratorBundle\Helper\StringHelper;

/**
 * Class InheritanceHandler
 *
 * @package Requestum\ApiGeneratorBundle\Service
 */
class InheritanceHandler
{
    /**
     * @var array
     */
    protected array $openApiSchema = [];

    /**
     * @var array
     */
    protected array $collection = [];

    /**
     * @param array $openApiSchema
     *
     * @return array
     */
    public function process(array $openApiSchema): array
    {
        $this->openApiSchema = $openApiSchema;

        if (!empty($this->openApiSchema['components']['schemas'])) {
            foreach ($this->openApiSchema['components']['schemas'] as $objectName => $objectData) {
                if (CommonHelper::isEntity($objectName)) {
                    if (!empty($objectData['allOf'])) {
                        $structuredObject = $this->processInheritanceStructure($objectData['allOf']);
                    } else {
                        $structuredObject = $this->processFlatStructure($objectData);
                    }
                    $this->collection[$objectName] = $structuredObject;
                }

            }
        }

        if (!empty($this->openApiSchema['components']['requestBodies'])) {
            foreach ($this->openApiSchema['components']['requestBodies'] as $objectName => $requestBody) {
                if (CommonHelper::isForm($objectName)) {
                    if (!empty($requestBody['content']['application/json']['schema'])) {
                        $objectData = $requestBody['content']['application/json']['schema'];

                        if (!empty($objectData['allOf'])) {
                            $structuredObject = $this->processInheritanceStructure($objectData['allOf']);
                        } else {
                            $structuredObject = $this->processFlatStructure($objectData);
                        }
                        $this->collection[$objectName] = $structuredObject;
                    }
                }
            }
        }

        return $this->collection;
    }

    /**
     * @param array $objectData
     *
     * @return array
     */
    private function processInheritanceStructure(array $objectData): array
    {
        $refs = array_filter($objectData, function ($el){
            return !empty($el['$ref']);
        });
        $objects = array_filter($objectData, function ($el){
            return !empty($el['type']);
        });

        $result = [];
        foreach ($objects as $object) {
            $result = array_merge_recursive($result, $this->processFlatStructure($object));
        }

        foreach ($refs as $ref) {
            $componentsSchemaData = CommonHelper::getComponentsSchemaDataByPath($this->openApiSchema, $ref['$ref']);
            if (!empty($componentsSchemaData['allOf'])) {
                $componentsSchemaData = $this->processInheritanceStructure($componentsSchemaData['allOf']);
                if (isset($componentsSchemaData['type'])) {
                    unset($componentsSchemaData['type']);
                }
                $result = array_merge_recursive($result, $componentsSchemaData);
            } else {
                if (isset($componentsSchemaData['type'])) {
                    unset($componentsSchemaData['type']);
                }
                $result = array_merge_recursive($result, $this->processFlatStructure($componentsSchemaData));
            }
        }

        return $result;
    }

    /**
     * @param array $objectData
     *
     * @return array
     */
    private function processFlatStructure(array $objectData): array
    {
        if (!empty($objectData['properties'])) {
            $objectData['properties'] = $this->processProperties($objectData['properties']);
        }

        return $objectData;
    }

    /**
     * @param array $properties
     *
     * @return array
     */
    private function processProperties(array $properties): array
    {
        $result = [];

        foreach ($properties as $name => $property) {
            if (!empty($property['$ref'])
                && !(
                    CommonHelper::isEntity(StringHelper::getReferencedSchemaObjectName($property['$ref']))
                    || CommonHelper::isForm(StringHelper::getReferencedSchemaObjectName($property['$ref']))
                )
            ) {
                $property = CommonHelper::getComponentsSchemaDataByPath($this->openApiSchema, $property['$ref']);
            }

            $result[$name] = $property;
        }

        return $result;
    }
}
