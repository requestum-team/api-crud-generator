<?php

namespace Requestum\ApiGeneratorBundle\Service\Annotations;

use Requestum\ApiGeneratorBundle\Helper\StringHelper;
use Requestum\ApiGeneratorBundle\Model\EntityProperty;
use Requestum\ApiGeneratorBundle\Model\Enum\PropertyTypeEnum;

/**
 * Class DoubleAnnotationGenerator
 *
 * @package Requestum\ApiGeneratorBundle\Service\Annotations
 */
class DecimalAnnotationGenerator implements AnnotationGeneratorInterface
{
    /**
     * @param EntityProperty $entityProperty
     *
     * @return string[]
     */
    public function generate(EntityProperty $entityProperty): array
    {
        $params = [
            'type' => PropertyTypeEnum::TYPE_DECIMAL,
            'name' => $entityProperty->getDatabasePropertyName(),
        ];

        if ($entityProperty->isNullable()) {
            $params['nullable'] = true;
        }

//        if ($entityProperty->isUnique()) {
//            $params['unique'] = true;
//        }

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
        return PropertyTypeEnum::TYPE_DECIMAL;
    }
}
