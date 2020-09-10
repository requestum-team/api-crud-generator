<?php

namespace Requestum\ApiGeneratorBundle\Service\Annotations;

use Requestum\ApiGeneratorBundle\Model\EntityProperty;

/**
 * Interface AnnotationGeneratorInterface
 *
 * @package Requestum\ApiGeneratorBundle\Service\Annotations
 */
interface AnnotationGeneratorInterface
{
    /**
     * @param EntityProperty $entityProperty
     * @param AnnotationRecord $annotationRecord
     *
     * @return AnnotationRecord
     */
    public function generate(EntityProperty $entityProperty, AnnotationRecord $annotationRecord): AnnotationRecord;

    /**
     * @param EntityProperty $entityProperty
     *
     * @return bool
     */
    public function support(EntityProperty $entityProperty): bool;
}
