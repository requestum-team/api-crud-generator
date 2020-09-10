<?php

namespace Requestum\ApiGeneratorBundle\Service\Annotations\Validation;

use Requestum\ApiGeneratorBundle\Model\EntityProperty;
use Requestum\ApiGeneratorBundle\Service\Annotations\AnnotationGenerator;
use Requestum\ApiGeneratorBundle\Service\Annotations\AnnotationGeneratorInterface;
use Requestum\ApiGeneratorBundle\Service\Annotations\AnnotationRecord;

class NotBlankAnnotationGenerator implements AnnotationGeneratorInterface
{
    /**
     * @inheritDoc
     */
    public function generate(EntityProperty $entityProperty, AnnotationRecord $annotationRecord): AnnotationRecord
    {
        return $annotationRecord
            ->addAnnotations(
                [
                    'Assert\NotBlank',
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
     * @inheritDoc
     */
    public function support(EntityProperty $entityProperty): bool
    {
        return $entityProperty->isRequired();
    }
}
