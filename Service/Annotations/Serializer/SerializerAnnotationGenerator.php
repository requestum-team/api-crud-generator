<?php

namespace Requestum\ApiGeneratorBundle\Service\Annotations\Serializer;

use Requestum\ApiGeneratorBundle\Helper\StringHelper;
use Requestum\ApiGeneratorBundle\Model\EntityProperty;
use Requestum\ApiGeneratorBundle\Service\Annotations\AnnotationGenerator;
use Requestum\ApiGeneratorBundle\Service\Annotations\AnnotationGeneratorInterface;
use Requestum\ApiGeneratorBundle\Service\Annotations\AnnotationRecord;

class SerializerAnnotationGenerator implements AnnotationGeneratorInterface
{
    public function generate(EntityProperty $entityProperty, AnnotationRecord $annotationRecord): AnnotationRecord
    {
        return $annotationRecord
            ->addAnnotations(
                [
                    sprintf(
                        'Serializer\Groups({%s})',
                        StringHelper::transformToDelimitedString($entityProperty->getSerializers())
                    ),
                ]
            )
            ->addUseSections(
                [
                    AnnotationGenerator::USE_SERIALIZER,
                ]
            )
        ;
    }

    public function support(EntityProperty $entityProperty): bool
    {
        return $entityProperty->isNeedSerializer();
    }
}
