<?php

namespace Requestum\ApiGeneratorBundle\Service\Annotations\Doctrine;

use Requestum\ApiGeneratorBundle\Helper\StringHelper;
use Requestum\ApiGeneratorBundle\Model\EntityProperty;
use Requestum\ApiGeneratorBundle\Model\Enum\PropertyTypeEnum;
use Requestum\ApiGeneratorBundle\Service\Annotations\AnnotationGeneratorInterface;
use Requestum\ApiGeneratorBundle\Service\Annotations\AnnotationRecord;

/**
 * Class FloatAnnotationGenerator
 *
 * @package Requestum\ApiGeneratorBundle\Service\Annotations
 */
class FloatAnnotationGenerator implements AnnotationGeneratorInterface
{
    /**
     * @param EntityProperty $entityProperty
     *
     * @return string[]
     */
    public function generate(EntityProperty $entityProperty): AnnotationRecord
    {
        $params = [
            'type' => PropertyTypeEnum::TYPE_FLOAT,
            'name' => $entityProperty->getDatabasePropertyName(),
        ];

        if ($entityProperty->isNullable()) {
            $params['nullable'] = true;
        }

        if ($entityProperty->isUnique()) {
            $params['unique'] = true;
        }

        return [
            sprintf(
                'ORM\Column(%s)',
                StringHelper::transformToEntityColumnParameters ($params)
            )
        ];
    }

    /**
     * @param EntityProperty $entityProperty
     *
     * @return bool
     */
    public function support(EntityProperty $entityProperty): bool
    {
        if ($entityProperty->getType() === PropertyTypeEnum::TYPE_NUMBER) {
            if (!is_null($entityProperty->getFormat()) && $entityProperty->getFormat() === PropertyTypeEnum::TYPE_FLOAT) {
                return true;
            }
        }

        return false;
    }
}
