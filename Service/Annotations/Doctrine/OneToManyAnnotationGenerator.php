<?php

namespace Requestum\ApiGeneratorBundle\Service\Annotations\Doctrine;

use Requestum\ApiGeneratorBundle\Model\EntityProperty;
use Requestum\ApiGeneratorBundle\Model\Enum\PropertyTypeEnum;
use Requestum\ApiGeneratorBundle\Service\Annotations\AnnotationGeneratorInterface;
use Requestum\ApiGeneratorBundle\Service\Annotations\AnnotationRecord;

/**
 * Class OneToManyAnnotationGenerator
 *
 * @package Requestum\ApiGeneratorBundle\Service\Annotations\Doctrine
 */
class OneToManyAnnotationGenerator implements AnnotationGeneratorInterface
{
    /**
     * @param EntityProperty $entityProperty
     *
     * @return AnnotationRecord
     */
    public function generate(EntityProperty $entityProperty): AnnotationRecord
    {
        // TODO: Implement generate() method.

        return new AnnotationRecord([]);
    }

    /**
     * @param EntityProperty $entityProperty
     *
     * @return bool
     */
    public function support(EntityProperty $entityProperty): bool
    {
        // TODO: Implement support() method.

        return $entityProperty->isOneToMany();
    }


}
