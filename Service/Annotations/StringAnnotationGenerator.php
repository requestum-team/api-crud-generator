<?php

namespace Requestum\ApiGeneratorBundle\Service\Annotations;

use Requestum\ApiGeneratorBundle\Helper\StringHelper;
use Requestum\ApiGeneratorBundle\Model\EntityProperty;
use Requestum\ApiGeneratorBundle\Model\Enum\PropertyTypeEnum;

/**
 * Class StringAnnotationGenerator
 *
 * @package Requestum\ApiGeneratorBundle\Service\Annotations
 */
class StringAnnotationGenerator implements AnnotationGeneratorInterface
{
    /**
     * @param EntityProperty $entityProperty
     *
     * @return string[]
     */
    public function generate(EntityProperty $entityProperty): array
    {
        $params = [
            'type' => PropertyTypeEnum::TYPE_STRING,
            'name' => $entityProperty->getDatabasePropertyName(),
        ];

        if ($entityProperty->isNullable()) {
            $params['nullable'] = true;
        }

//        if ($entityProperty->isUnique()) {
//            $params['unique'] = true;
//        }

        if (!is_null($entityProperty->getMaxLength())) {
            $params['length'] = $entityProperty->getMaxLength();
        }

        return [
            sprintf(
                'ORM\Column(%s)',
                StringHelper::transformToEntityColumnParameters ($params)
            )
        ];
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return PropertyTypeEnum::TYPE_STRING;
    }
}
