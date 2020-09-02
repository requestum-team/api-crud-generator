<?php

namespace Requestum\ApiGeneratorBundle\Service\Annotations\Validation;

use Requestum\ApiGeneratorBundle\Helper\StringHelper;
use Requestum\ApiGeneratorBundle\Model\EntityProperty;
use Requestum\ApiGeneratorBundle\Service\Annotations\AnnotationGenerator;
use Requestum\ApiGeneratorBundle\Service\Annotations\AnnotationGeneratorInterface;
use Requestum\ApiGeneratorBundle\Service\Annotations\AnnotationRecord;

class CountAnnotationGenerator implements AnnotationGeneratorInterface
{
    /**
     * @inheritDoc
     */
    public function generate(EntityProperty $entityProperty, AnnotationRecord $annotationRecord): AnnotationRecord
    {
        $options = [];

        !$entityProperty->getMinItems() ?: $options['min'] = $entityProperty->getMinItems();
        !$entityProperty->getMaxItems() ?: $options['max'] = $entityProperty->getMaxItems();


        return $annotationRecord
            ->addAnnotations(
                [
                    sprintf('Assert\Count(%s)', StringHelper::transformToEntityColumnParameters($options)),
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
        return ($entityProperty->getMinItems() || $entityProperty->getMaxItems());
    }
}
