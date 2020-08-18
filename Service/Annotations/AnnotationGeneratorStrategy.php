<?php

namespace Requestum\ApiGeneratorBundle\Service\Annotations;

/**
 * Class AnnotationGeneratorStrategy
 *
 * @package Requestum\ApiGeneratorBundle\Service\Annotations
 */
class AnnotationGeneratorStrategy
{
    /**
     * @var array
     */
    private $generators = [
        PrimaryAutoAnnotationGenerator::class,
        StringAnnotationGenerator::class,
        FloatAnnotationGenerator::class,
        DecimalAnnotationGenerator::class,
        IntegerAnnotationGenerator::class,
    ];

    /**
     * @var array
     */
    private $storage = [];

    /**
     * AnnotationGeneratorStrategy constructor.
     */
    public function __construct()
    {
        foreach ($this->generators as $generator) {
            $this->registerGenerator($generator);
        }
    }

    /**
     * @param string $type
     *
     * @return AnnotationGeneratorInterface
     *
     * @throws \Exception
     */
    public function getAnnotationGenerator(string $type): AnnotationGeneratorInterface
    {
        if (!array_key_exists($type, $this->storage)) {
            throw new \Exception(sprintf('Annotation generator for type %s not found', $type));
        }

        return $this->storage[$type];
    }

    /**
     * @param string $generator
     */
    private function registerGenerator(string $generator)
    {
        /** @var AnnotationGeneratorInterface $object */
        $object = new $generator();
        if (!array_key_exists($object->getType(), $this->storage)) {
            $this->storage[$object->getType()] = $object;
        }
    }
}
