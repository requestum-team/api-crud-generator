<?php

namespace Requestum\ApiGeneratorBundle\Service\Annotations\Validation;

use Requestum\ApiGeneratorBundle\Model\EntityProperty;
use Requestum\ApiGeneratorBundle\Service\Annotations\AnnotationGenerator;
use Requestum\ApiGeneratorBundle\Service\Annotations\AnnotationGeneratorInterface;
use Requestum\ApiGeneratorBundle\Service\Annotations\AnnotationRecord;

class RegexAnnotationGenerator implements AnnotationGeneratorInterface
{
    /**
     * @inheritDoc
     */
    public function generate(EntityProperty $entityProperty, AnnotationRecord $annotationRecord): AnnotationRecord
    {
        return $annotationRecord
            ->addAnnotations(
                [
                    sprintf('Assert\Regex("%s")', $entityProperty->getPattern()),
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
        return (bool)$entityProperty->getPattern();
    }
}
