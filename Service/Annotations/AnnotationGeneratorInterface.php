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
    public function generate(EntityProperty $entityProperty): array;

    /**
     * @return string
     */
    public function getType(): string;
}
