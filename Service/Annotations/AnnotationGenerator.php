<?php

namespace Requestum\ApiGeneratorBundle\Service\Annotations;

use Requestum\ApiGeneratorBundle\Model\EntityProperty;
use Requestum\ApiGeneratorBundle\Service\Annotations\Doctrine;
use Requestum\ApiGeneratorBundle\Service\Annotations\Validation;
use Requestum\ApiGeneratorBundle\Service\Annotations\Serializer;
use Requestum\ApiGeneratorBundle\Service\Annotations\Custom;

/**
 * Class AnnotationGenerator
 *
 * @package Requestum\ApiGeneratorBundle\Service\Annotations
 */
class AnnotationGenerator
{
    const USE_ORM = 'Doctrine\ORM\Mapping as ORM';
    const USE_CONSTRAINTS = 'Symfony\Component\Validator\Constraints as Assert';
    const USE_SERIALIZER  = 'Symfony\Component\Serializer\Annotation as Serializer';
    const USE_REFERENCE   = 'Requestum\ApiBundle\Rest\Metadata\Reference';

    /**
     * @var array
     */
    private array $generators = [
        //ORM
        Doctrine\ArrayAnnotationGenerator::class,
        Doctrine\DecimalAnnotationGenerator::class,
        Doctrine\FloatAnnotationGenerator::class,
        Doctrine\IntegerAnnotationGenerator::class,
        Doctrine\PrimaryAutoAnnotationGenerator::class,
        Doctrine\StringAnnotationGenerator::class,
        Doctrine\ManyToOneAnnotationGenerator::class,
        Doctrine\ManyToManyAnnotationGenerator::class,
        Doctrine\OneToManyAnnotationGenerator::class,
        Doctrine\OneToOneAnnotationGenerator::class,
        //Validation
        Validation\UniqueAnnotationGenerator::class,
        Validation\NotBlankAnnotationGenerator::class,
        //Serializer
        Serializer\SerializerAnnotationGenerator::class,
        //Custom
        Custom\ReferenceAnnotationGenerator::class,
    ];

    /**
     * @var array
     */
    private array $storage = [];

    /**
     * AnnotationGenerator constructor.
     */
    public function __construct()
    {
        foreach ($this->generators as $generator) {
            $object = new $generator();
            $this->storage[] = $object;
        }
    }

    /**
     * @param EntityProperty $entityProperty
     *
     * @return AnnotationRecord
     */
    public function getAnnotationRecord(EntityProperty $entityProperty): AnnotationRecord
    {
        $annotationRecord = new AnnotationRecord();

        /** @var AnnotationGeneratorInterface $object */
        foreach ($this->storage as $object) {
            if ($object->support($entityProperty)) {
                $object->generate($entityProperty, $annotationRecord);
            }
        }

        return $annotationRecord;
    }
}
