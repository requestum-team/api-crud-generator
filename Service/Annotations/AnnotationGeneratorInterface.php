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
     *
     * @return array
     */
    public function generate(EntityProperty $entityProperty): AnnotationRecord;

    /**
     * @param EntityProperty $entityProperty
     *
     * @return bool
     */
    public function support(EntityProperty $entityProperty): bool;
}
