<?php

namespace Requestum\ApiGeneratorBundle\Service\Annotations\Doctrine;

use Requestum\ApiGeneratorBundle\Helper\StringHelper;
use Requestum\ApiGeneratorBundle\Model\EntityProperty;
use Requestum\ApiGeneratorBundle\Model\Enum\PropertyTypeEnum;
use Requestum\ApiGeneratorBundle\Service\Annotations\AnnotationGeneratorInterface;
use Requestum\ApiGeneratorBundle\Service\Annotations\AnnotationRecord;

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
    public function generate(EntityProperty $entityProperty): AnnotationRecord
    {
        $params = [
            'type' => PropertyTypeEnum::TYPE_DECIMAL,
            'name' => $entityProperty->getDatabasePropertyName(),
        ];

        if ($entityProperty->isNullable()) {
            $params['nullable'] = true;
        }

        if ($entityProperty->isUnique()) {
            $params['unique'] = true;
        }

        return new AnnotationRecord([
            sprintf(
                'ORM\Column(%s)',
                StringHelper::transformToEntityColumnParameters ($params)
            )
        ]);
    }

    /**
     * @param EntityProperty $entityProperty
     *
     * @return bool
     */
    public function support(EntityProperty $entityProperty): bool
    {
        if ($entityProperty->getType() === PropertyTypeEnum::TYPE_NUMBER) {
            if (!is_null($entityProperty->getFormat()) && $entityProperty->getFormat() === PropertyTypeEnum::TYPE_DOUBLE) {
                return true;
            }
        }

        return false;
    }
}
