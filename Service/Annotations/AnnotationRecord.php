<?php

namespace Requestum\ApiGeneratorBundle\Service\Annotations;

/**
 * Class AnnotationRecord
 *
 * @package Requestum\ApiGeneratorBundle\Service\Annotations
 */
class AnnotationRecord
{
    /**
     * @var array
     */
    private array $annotation = [];

    /**
     * @var array
     */
    private array $useSection = [];

    /**
     * AnnotationRecord constructor.
     *
     * @param array $annotation
     * @param array $useSection
     */
    public function __construct(array $annotation, array $useSection = [])
    {
        $this->annotation = $annotation;
        $this->useSection = $useSection;
    }

    /**
     * @return array
     */
    public function getAnnotation(): array
    {
        return array_map(function ($el){
            return ['name' => $el];
        }, $this->annotation);
    }

    /**
     * @param array $annotation
     *
     * @return AnnotationRecord
     */
    public function setAnnotation(array $annotation)
    {
        $this->annotation = $annotation;

        return $this;
    }

    /**
     * @return array
     */
    public function getUseSection(): array
    {
        return $this->useSection;
    }

    /**
     * @param array $useSection
     *
     * @return AnnotationRecord
     */
    public function setUseSection(array $useSection)
    {
        $this->useSection = $useSection;

        return $this;
    }
}
