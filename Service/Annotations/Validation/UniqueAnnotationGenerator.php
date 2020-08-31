<?php

namespace Requestum\ApiGeneratorBundle\Service\Annotations\Validation;

use Requestum\ApiGeneratorBundle\Model\EntityProperty;
use Requestum\ApiGeneratorBundle\Service\Annotations\AnnotationGeneratorInterface;
use Requestum\ApiGeneratorBundle\Service\Annotations\AnnotationRecord;
use Requestum\ApiGeneratorBundle\Service\Annotations\AnnotationGenerator;

class UniqueAnnotationGenerator implements AnnotationGeneratorInterface
{
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

    public function support(EntityProperty $entityProperty): bool
    {
        return $entityProperty->isUnique();
    }
}
