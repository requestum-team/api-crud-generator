<?php

namespace Requestum\ApiGeneratorBundle\Service\Annotations\Validation;

use Requestum\ApiGeneratorBundle\Model\EntityProperty;
use Requestum\ApiGeneratorBundle\Model\Enum\PropertyTypeEnum;
use Requestum\ApiGeneratorBundle\Service\Annotations\AnnotationGeneratorInterface;
use Requestum\ApiGeneratorBundle\Service\Annotations\AnnotationRecord;
use Requestum\ApiGeneratorBundle\Service\Annotations\AnnotationGenerator;

/**
 * Class UniqueAnnotationGenerator
 *
 * @package Requestum\ApiGeneratorBundle\Service\Annotations\Validation
 */
class UniqueAnnotationGenerator implements AnnotationGeneratorInterface
{
    /**
     * @param EntityProperty $entityProperty
     * @param AnnotationRecord $annotationRecord
     *
     * @return AnnotationRecord
     */
    public function generate(EntityProperty $entityProperty, AnnotationRecord $annotationRecord): AnnotationRecord
    {
        return $annotationRecord
            ->addAnnotations(
                [
                    'Assert\Unique',
                ]
            )
            ->addUseSections(
                [
                    AnnotationGenerator::USE_CONSTRAINTS,
                ]
            )
        ;
    }

    /**
     * @param EntityProperty $entityProperty
     *
     * @return bool
     */
    public function support(EntityProperty $entityProperty): bool
    {
        return
            $entityProperty->getType() === PropertyTypeEnum::TYPE_ARRAY
            && $entityProperty->isUnique()
        ;
    }
}
