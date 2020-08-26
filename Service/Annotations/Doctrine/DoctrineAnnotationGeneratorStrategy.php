<?php

namespace Requestum\ApiGeneratorBundle\Service\Annotations\Doctrine;

use Requestum\ApiGeneratorBundle\Model\EntityProperty;
use Requestum\ApiGeneratorBundle\Service\Annotations\AnnotationGeneratorInterface;

/**
 * Class DoctrineAnnotationGeneratorStrategy
 *
 * @package Requestum\ApiGeneratorBundle\Service\Annotations
 */
class DoctrineAnnotationGeneratorStrategy
{
    /**
     * @var array
     */
    private $generators = [
        ArrayAnnotationGenerator::class,
        DecimalAnnotationGenerator::class,
        FloatAnnotationGenerator::class,
        IntegerAnnotationGenerator::class,
        PrimaryAutoAnnotationGenerator::class,
        StringAnnotationGenerator::class,
        ManyToOneAnnotationGenerator::class,
        ManyToManyAnnotationGenerator::class,
        OneToManyAnnotationGenerator::class,
        OneToOneAnnotationGenerator::class,
    ];

    /**
     * @var array
     */
    private $storage = [];

    /**
     * DoctrineAnnotationGeneratorStrategy constructor.
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
     * @return AnnotationGeneratorInterface
     *
     * @throws \Exception
     */
    public function getAnnotationGenerator(EntityProperty $entityProperty): AnnotationGeneratorInterface
    {
        $generator = null;
        /** @var AnnotationGeneratorInterface $object */
        foreach ($this->storage as $object) {
            if ($object->support($entityProperty)) {
                $generator = $object;
                break;
            }
        }
        if (is_null($generator)) {
            throw new \Exception(sprintf('Annotation generator for property %s not found', $entityProperty->getName()));
        }

        return $generator;
    }
}
