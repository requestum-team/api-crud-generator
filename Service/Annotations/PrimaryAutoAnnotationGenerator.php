<?php

namespace Requestum\ApiGeneratorBundle\Service\Annotations;

use Requestum\ApiGeneratorBundle\Model\EntityProperty;
use Requestum\ApiGeneratorBundle\Model\Enum\PropertyTypeEnum;

/**
 * Class PrimaryAutoAnnotationGenerator
 *
 * @package Requestum\ApiGeneratorBundle\Service\Annotations
 */
class PrimaryAutoAnnotationGenerator implements AnnotationGeneratorInterface
{
    /**
     * @param EntityProperty $entityProperty
     *
     * @return string[]
     */
    public function generate(EntityProperty $entityProperty): array
    {
        return [
            'ORM\Id',
            'ORM\Column(type="integer")',
            'ORM\GeneratedValue(strategy="AUTO")',
        ];
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return PropertyTypeEnum::TYPE_PRIMARY_AUTO;
    }

}
